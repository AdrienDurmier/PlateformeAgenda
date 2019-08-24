<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CheckExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('check', [$this, 'displayCheck']),
        ];
    }

    public function displayCheck($entity)
    {
        $response = '';
        if($entity !== null){
            switch ($entity) {
                case 1:
                    $response = "<span class='text-success'><i class='fas fa-check'></i></span>";
                    break;
                case 0:
                    $response = "<span class='text-danger'><i class='fas fa-times'></i></span>";
                    break;
            }
        }
        return $response;
    }
}
