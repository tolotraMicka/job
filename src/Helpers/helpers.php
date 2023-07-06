<?php 
    namespace App\Helpers;
    use DateTime;
    use Twig\Extension\AbstractExtension;
    use Twig\TwigFunction;

    class Helper extends AbstractExtension
    {
        public function getFunctions()
        {
            return [
                new TwigFunction('getDiffDate', [$this, 'getDiffDate']),
                new TwigFunction('dateInFr', [$this, 'dateInFr']),
                new TwigFunction('tempsToString', [$this, 'tempsToString']),
            ];
        }

        public static function getDiffDate($date_publication)
        {
            $date1 = new DateTime($date_publication);

            //prendre la date d'aujourd'hui
            $date2 = new DateTime();

            //prendre la différence entre la date d'aujourd'hui et la date de publication
            $interval = $date1->diff($date2);

            //retourner cette différence en format jour
            return $interval->format('%a');
        }

        public static function dateInFr($date) 
        {
            $date = new DateTime($date);
            return $date->format('d/m/Y');
        }

        public static function tempsToString($tempsInInt) {
            switch ($tempsInInt) {
                case 1:
                    return "Temps partiel";
                    break;
                case 2:
                    return "Temps plein";
                    break;
            }
        }
    }

?> 
