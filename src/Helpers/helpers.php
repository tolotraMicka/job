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
            ];
        }

        public static function getDiffDate($date_publication)
        {
            $date1 = new DateTime($date_publication);
            $date2 = new DateTime();

            $interval = $date1->diff($date2);
            return $interval->format('%a');
        }

        public static function dateInFr($date) 
        {
            $date = new DateTime($date);
            return $date->format('d/m/Y');
        }
    }

?> 
