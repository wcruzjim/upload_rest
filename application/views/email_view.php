<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<title></title>
	<!--[if mso]>
	<noscript>
		<xml>
			<o:OfficeDocumentSettings>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
	</noscript>
	<![endif]-->
	<style>
		table, td, div, h1, p {font-family: Arial, sans-serif;}
	</style>
</head>
<body style="margin:0;padding:0;">
	<div role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
		<div>
			<div style="padding:0;">
				<div role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
					<div>
						<div style="background:#032556;">
						<h1 style="color:white;font-size:80px">KONECTA</h1>
						</div>
					</div>
					<div>
						<div style="padding:36px 30px 42px 30px;">
							<div role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
								<div>
									<div style="padding:0 0 36px 0;color:#153643;">
										<h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hola, </h1>
										<?php if(!empty($token)):?>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
											Te informamos que se generó el siguiente código <strong><?=$token?></strong> para que puedas ingresar a JARVIS.
											</p>
										<?php elseif(!empty($qr)):?>
											
											<h3>Para configurar la verificación de dos pasos, haz lo siguiente:</h3>

											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
												1- Abra la aplicación de 2FA en su dispositivo móvil (Google Authenticator, FortiToken)
											</p>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
												2- Pulse la opción para escanear un código QR. Busque un icono de cámara o código QR.
											</p>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
												3- Escanee el código QR.
											</p>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
												4- La aplicación de 2FA generará un código único de 6 dígitos.
											</p>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
											5- Listo. Deberás ingresar el código de 6 dígitos
											que se muestra en la aplicación de 2FA (Google Authenticator, FortiToken) para ingresar a JARVIS.

											</p>
										<?php elseif(!empty($codigo_verificacion)):?>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
											Te informamos que se generó el siguiente código <strong><?=$codigo_verificacion?></strong> para verificar tu correo.
											</p>
										<?php else :?>
													<p></p>
										<?php endif ?>

										</div>
								</div>
								<?php if(!empty($qr)):?>
									<div>
										<div style="padding:0;">
											<div role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
												<div >
													<div style="width:260px;padding:0;vertical-align:top;color:#153643;">
														<p style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><img src="<?= $qr ?>" alt="" width="260" style="height:auto;display:block;" /></p>
														<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
														</p>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif ?>

							</div>
						</div>
					</div>
					<div>
						<div style="padding:30px;background:#032556;">
							<div role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
								<div>
									<div style="padding:0;width:50%;" >
										<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
											&reg; KONECTA  <?php echo date('Y'); ?><br/>
										</p>
									</div>
									<div style="padding:0;width:50%;" >
										<div role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
											<div>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>