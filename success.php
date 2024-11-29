<?php
$minhahash = $_GET['hash'];


getHash($minhahash);


function getHash($hash){
        // Substitua pela URL correta da sua API
            $urlApi = "http://14720.masterdaweb.net:3006/transaction?hash=$hash";
            //http://45.40.96.151:3000/saldo?carteira=0x98BACA5aE161344E5AA78009D458E74fcF2535aB&rede=bsc&contrato=0xdd668c880B6ab478FbfC23E3aE5a6a8872152B50
            // Inicializa o cURL
            $ch = curl_init($urlApi);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            
            // Desabilita a verificação de SSL
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            // Executa a chamada cURL
            $response = curl_exec($ch);
            if ($response === false) {
                //echo 'Erro cURL: ' . curl_error($ch);
            }
            
            curl_close($ch);
            
            // Decodifica a resposta JSON
            $resultado = json_decode($response, true);
            
            if($resultado) {
                // Obtém o saldo da resposta
                $msg = $resultado['message'];
                if($msg=="success"){
                    echo "<p style='color: blue'>Tokens purchased successfully!</p>";
                }
                //Transação processada com sucesso.
                return $msg;
            } else {
                echo "Error: " . htmlspecialchars($response);
            }
}


?>