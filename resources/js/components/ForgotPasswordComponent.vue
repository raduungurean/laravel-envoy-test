<template>
    <section class="slice slice-sm">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <h3>Set new password</h3>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div v-if="showSuccess" class="alert alert-success pt-2 pb-2" role="alert">
                    Password updated.
                </div>
                <div v-if="showError" class="alert alert-danger pt-2 pb-2" role="alert">
                    There was an error resetting the password.
                </div>
            </div>
        </div>
        <div class="row" v-if="!showSuccess">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <form autocomplete="off" @submit.prevent="requestResetPassword" method="post">
                    <div class="form-group">
                        <input v-bind:class="{ 'is-invalid': error && this.password.length }" class="form-control form-control-md" type="password" placeholder="Password *" required v-model="password" @keyup="matchesPassword()"  />
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-md" type="password" placeholder="Confirm new password *" required v-model="passwordConfirmation" @keyup="matchesPassword()" />
                    </div>
                    <div class="alert alert-info pt-2 pb-2" role="alert">
                        <span class="icon-warning"><i class="fa fa-exclamation-circle"></i></span>&nbsp;
                        <span class="text-secondary text-sm text-dark">The password must include: lowercase letters, uppercase letters and digits.</span>
                    </div>
                    <div class="text-center">
                        <button type="submit" v-bind:class="{ disabled: error }" class="btn btn-block btn-md btn-primary mt-4">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>

    const schema = new passwordValidator();
    schema
        .is().min(5)
        .is().max(100)
        .has().uppercase()
        .has().lowercase()
        .has().digits();

    export default {
        props: ['hash', 'id'],
        data: () => {
            return {
                password: '',
                passwordConfirmation: '',
                error: true,
                showSuccess: false,
                showError: false,
            }
        },
        methods: {
            requestResetPassword() {
                this.showSuccess = false;
                this.showError = false;

                axios.post("/api/password", {
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                    hash: this.hash,
                    id: this.id
                }).then(result => {
                    this.response = result.data;
                    if (this.response.success) {
                        this.showSuccess = true;
                    } else {
                        this.showError = true;
                    }
                }, error => {
                    this.showError = true;
                    console.error(error);
                });
            },
            matchesPassword() {
                this.error = true;
                this.showSuccess = false;
                this.showError = false;
                const validPassword = schema.validate(this.password, { list: true });
                const isValid = validPassword.length === 0;

                if (isValid && this.password === this.passwordConfirmation) {
                    this.error = false;
                }
            }
        },
        mounted() {

        }
    }
</script>
