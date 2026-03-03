<?php
function enviar_whatsapp_cita($numero, $mensaje) {
    // La estructura exacta que Evolution API exige para bloquear la tarjeta
    $data = [
        "number" => "51" . $numero,
        "text" => $mensaje,
        "linkPreview" => false // 🚫 ESTE COMANDO AHORA VA EN LA RAÍZ
    ];

    $ch = curl_init(WA_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "apikey: " . WA_API_KEY
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}