@extends('layouts.email')
@section('content')
    <tr>
        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hello</h1>
                            <p>You have been invited by {{$user}} to join {{$app_name}}. Please login in the app and check your pending invitation requests.</p>
                            <p>If you have any questions, feel free to <a href="mailto:{{$support_email}}">email us</a>. (We're lightning quick at replying)</p>
                            <p>Thanks,<br>{{$app_user}} and the {{$app_name}} Team</p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop
