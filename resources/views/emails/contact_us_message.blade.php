@extends('layouts.email')

@section('content')
<table style="width: 100%;">
	<tr>
		<td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;max-width:100vw;padding:32px;">
			<h1 style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;color:#3d4852;font-size:18px;font-weight:bold;margin-top:0;text-align:left;">Hello!</h1>

			<p style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;font-size:16px;line-height:1.5em;margin-top:0;text-align:left;">You have a message from visitor below:</p>

			<table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;margin:30px auto;padding:0;text-align:center;width:100%;">
				<tbody>
					<tr>
						<td align="center" style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;">
								<tbody>
									<tr>
										<td align="center" style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;">
											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;">
												<tbody>
													<tr>
														<td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position:relative;">
															<ul style="width:570px;padding-left:15px;">
																<li>{{ 'From: '.$full_name }}</li>
																<li>{{ 'Email: '.$email }}</li>
																<li>{{ 'Phone number: '.$phone_number }}</li>
																<li>{{ 'Company Name: '.$company_name }}</li>
																<li>{{ 'Message:' }} {!! str_replace("\n", "<br />", $body_message) !!}</li>
															</ul>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
@endsection