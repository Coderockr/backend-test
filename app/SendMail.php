<?php

namespace App;


class SendMail
{

    public static function send($subject, $to, $data)
    {
        $emailBody = self::createBody($data);
        self::sendMail($subject, $to, $emailBody);
    }

    private function sendMail($subject, $to, $body)
    {

        $transport = (new \Swift_SmtpTransport('ENDEREÇO SMTP', 'PORTA SMTP'))
            ->setUsername('USUARIO')
            ->setPassword('SENHA')
        ;

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($subject))
            ->setFrom(['teste@coderockr.com' => 'Teste CodeRockr'])
            ->setTo($to)
            ->setBody($body, 'text/html')
        ;

        $result = $mailer->send($message);

        return $result;
    }

    private function createBody($arr)
    {
        $head = "<tr>";
        $row = "<tr>";
        foreach ($arr as $k => $v) {
            $head .= "<th>{$k}</th>";
            $row .= "<td>{$v}</td>";
        }
        $head .= "</tr>";
        $row .= "</tr>";

        $body = "<style>
                        table {
                          font-family: arial, sans-serif;
                          border-collapse: collapse;
                          width: 100%;
                        }
                        
                        td, th {
                          border: 1px solid #dddddd;
                          text-align: left;
                          padding: 8px;
                        }
                        
                        tr:nth-child(even) {
                          background-color: #dddddd;
                        }
                    </style>
                    <table>{$head}{$row}</table>
                    ";

        return $body;
    }

}