@extends('layouts.email')
@section('content')
<tr>
    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="content-cell">
                    <div class="f-fallback">
                        <h1>Welcome, {{$user->first_name}}!</h1>
                        <p>Thanks for trying {{$app_name}}. We’re thrilled to have you on board. To get the most out of {{$app_name}}, do this primary next step:</p>
                        <table class="body-action" align="center" width="100%" cellpadding="0"
                               cellspacing="0" role="presentation">
                            <tr>
                                <td align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                           role="presentation">
                                        <tr>
                                            <td align="center">
                                                <a style="color: #fff" href="{{$action_url}}" class="f-fallback button" target="_blank">Verify your email address</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <p>If you have any questions, feel free to <a href="mailto:{{$support_email}}">email us</a>. (We're lightning quick at replying)</p>
                        <p>Thanks,<br>{{$app_user}} and the {{$app_name}} Team</p>
                        <table class="body-sub" role="presentation">
                            <tr>
                                <td>
                                    <p class="f-fallback sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                    <p class="f-fallback sub">{{$action_url}}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
@stop
