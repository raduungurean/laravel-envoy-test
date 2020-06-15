<template>
    <section class="slice slice-sm">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <h2>Invitation</h2>
                <h5><strong>{{`${user.first_name} ${user.last_name}`}}</strong> {{`invited you to join`}} <strong>{{`${group.name}`}}</strong> group.</h5>
            </div>
        </div>
        <div v-if="message" class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <p><strong>Message from {{`${user.first_name} ${user.last_name}`}}</strong>: {{message}}</p>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div v-if="showSuccess" class="alert alert-success pt-2 pb-2" role="alert">
                    Successfully registered.
                </div>
                <div v-if="showError" class="alert alert-danger pt-2 pb-2" role="alert">
                    There was an error registering.
                </div>
            </div>
        </div>
        <div class="row" v-if="!showSuccess">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <form autocomplete="off" @submit.prevent="acceptInvite" method="post">
                    <div class="text-center">
                        <div class="form-group">
                            <input
                                v-bind:class="{ 'is-invalid': $v.firstName.$error }"
                                class="form-control form-control-md"
                                type="text"
                                placeholder="First Name *"
                                required
                                v-model="firstName"
                                @input="setFirstName($event.target.value)"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-bind:class="{ 'is-invalid': $v.lastName.$error }"
                                class="form-control form-control-md"
                                type="text"
                                placeholder="Last Name *"
                                required
                                v-model="lastName"
                                @input="setLastName($event.target.value)"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-bind:class="{ 'is-invalid': $v.email.$error }"
                                class="form-control form-control-md"
                                type="text"
                                placeholder="Email *"
                                required
                                v-model="email"
                                @input="setEmail($event.target.value)"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-bind:class="{ 'is-invalid': $v.password.$error }"
                                class="form-control form-control-md"
                                type="password"
                                placeholder="Password *"
                                required
                                v-model="password"
                                @input="setPassword($event.target.value)"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-bind:class="{ 'is-invalid': $v.passwordConfirmation.$error }"
                                class="form-control form-control-md"
                                type="password"
                                placeholder="Confirm new password *"
                                required
                                v-model="passwordConfirmation"
                                @input="setPasswordConfirmation($event.target.value)"
                            />
                        </div>
                        <button
                            type="submit"
                            v-bind:class="{ disabled: $v.$invalid }"
                            class="btn btn-block btn-md btn-primary mt-4">
                            Accept
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['invite'],
        data: function () {
            const invite = JSON.parse(this.invite);
            return {
                user: invite.user,
                group: invite.group,
                message: invite.message,
                showSuccess: false,
                showError: false,
                error: true,
                password: '',
                hash: invite.hash,
                passwordConfirmation: '',
                firstName: '',
                lastName: '',
                email: invite.to_email,
            };
        },
        validations: {
            firstName: {
                required,
                minLength: minLength(2)
            },
            lastName: {
                required,
                minLength: minLength(2)
            },
            email: {
                required,
                email,
            },
            password: {
                required,
                minLength: minLength(5)
            },
            passwordConfirmation: {
                sameAsPassword: sameAs('password')
            },
        },
        methods: {
            setFirstName(value) {
                this.firstName = value;
                this.$v.firstName.$touch();
            },
            setLastName(value) {
                this.lastName = value;
                this.$v.lastName.$touch();
            },
            setEmail(value) {
                this.email = value;
                this.$v.email.$touch();
            },
            setPassword(value) {
                this.password = value;
                this.$v.password.$touch();
            },
            setPasswordConfirmation(value) {
                this.passwordConfirmation = value;
                this.$v.passwordConfirmation.$touch();
            },
            acceptInvite() {
                this.showSuccess = false;
                this.showError = false;
                axios.post("/api/accept-invite", {
                    first_name: this.firstName,
                    last_name: this.lastName,
                    password: this.password,
                    hash: this.hash,
                    email: this.email,
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
        },
        mounted() {

            console.log('### this.invite', this.invite);
        }
    }
</script>
