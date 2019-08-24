<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BooleanPictoExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('display_boolean_picto', [$this, 'displayBooleanPicto']),
        ];
    }

    public function displayBooleanPicto($entity)
    {
        $response = '';
        if($entity !== null){
            switch ($entity) {
                case 1:
                    $response = '<i class="fas fa-check-circle text-success"></i>';
                    break;
                case 0:
                    $response =  '<i class="fas fa-times-circle text-danger"></i>';
                    break;
            }
        }
        return $response;
    }
}
