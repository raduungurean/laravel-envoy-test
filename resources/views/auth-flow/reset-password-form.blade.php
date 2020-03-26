@extends('layouts.default')
@section('content')
    <section class="slice slice-sm" id="sct-form-contact">
        <div class="container px-0">
            <div class="row justify-content-left mb-2">
                <div class="col-lg-6">
                    <h3>Set new password</h3>
                </div>
            </div>
            <div class="row justify-content-left">
                <div class="col-lg-6">
                    <form>
                        <div class="form-group">
                            <input class="form-control form-control-md" type="password" placeholder="Password *" required />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-md" type="password" placeholder="Confirm new password *" required />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-block btn-sm btn-primary mt-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop
