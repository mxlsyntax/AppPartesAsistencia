<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header('Content-Type: application/json');    

    require 'vendor/autoload.php'; // Incluye el autoload de Composer

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging\CloudMessage;
    use Kreait\Firebase\Messaging\Notification;
    use Kreait\Firebase\Exception\Messaging\MessagingException;

    // Ruta segura al archivo de cuenta de servicio (ajusta según tu caso)
    $serviceAccountPath = './../prueba-notificaciones-global-firebase-adminsdk-fbsvc-50d576329b.json';
    if (!file_exists($serviceAccountPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Archivo de cuenta de servicio no encontrado']);
        exit;
    }

    $factory = (new Factory)->withServiceAccount($serviceAccountPath);
    $messaging = $factory->createMessaging();

    // Añadir un log para ver si se está llegando al archivo PHP
    file_put_contents('log.txt', "Llegada a enviar_notificacion.php\n", FILE_APPEND);

    // Leer datos POST
    $input = json_decode(file_get_contents("php://input"), true);
    if ($input === null) {
        // Si hay un error con el JSON, logueamos el error
        file_put_contents('log.txt', "Error de JSON: " . json_last_error_msg() . "\n", FILE_APPEND);
    } else {
        file_put_contents('log.txt', "Datos recibidos: " . print_r($input, true) . "\n", FILE_APPEND);
    }
    //var_dump($input); // Verifica el contenido del token
    $token = $input['token'] ?? '';
    $titulo = $input['titulo'] ?? 'Pedidos nuevos';
    $cuerpo = $input['cuerpo'] ?? 'Tienes un nuevo pedido pendiente';
    $url = $input['url'] ?? 'https://globalsystem.es/AppWeb/PortalCemento/avisos_bus.php';

    if (!$token) {
        echo json_encode(['status' => 'error', 'message' => 'Token vacío']);
        exit;
    }

    try {
        $notification = Notification::create($titulo, $cuerpo);
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withWebPushConfig([
                'fcm_options' => [
                    'link' => $url
                ],
                'notification' => [
                    'icon' => 'https://globalsystem.es/AppWeb/PortalCemento/assets/icons/icon-96x96.png',
                    'click_action' => $url
                ]
            ]);

        $messaging->send($message);

        echo json_encode(['status' => 'ok', 'message' => 'Notificación enviada correctamente']);
    } catch (MessagingException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar notificación', 'error' => $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error general', 'error' => $e->getMessage()]);
    }


?>

