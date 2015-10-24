<?php

class HTMLView {

    public function echoHTML($body) {

        if($body == NULL){
            throw new \Exception("Body is null");
        }

        echo "
				<!DOCTYPE html>
				<html>
				<head>
				<meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <title>PHP||MovieQuiz</title>
                <link rel='stylesheet' href='css/bootstrap.min.css'>
				</head>
				<body>
				 <div class='container'>
					$body
                  </div>
				</body>

        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='js/bootstrap.js'></script>
        <script type='text/javascript' src='js/bootstrap.min.js'></script> 
				</html>";

    }

}