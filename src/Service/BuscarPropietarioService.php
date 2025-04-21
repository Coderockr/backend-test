<?php 

namespace App\Service;

use App\Controller\Exceptions\NaoEncontrouPropietarioException;
use App\Entity\Propietario;
use App\Repository\PropietarioRepository;


class BuscarPropietarioService
{
    public function __construct(
        private PropietarioRepository $propietarioRepository,
    )
    {
    }

    public function BuscarPropietarioOufalhar(int $id):Propietario
    {
        $propietario = $this->propietarioRepository->find($id);

        if(null === $propietario){
            throw new NaoEncontrouPropietarioException();
        }
        return $propietario;
    }
}