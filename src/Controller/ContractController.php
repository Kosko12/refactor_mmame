<?php

namespace App\Controller;

use App\Repository\ContractRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response;

class ContractController
{
    public function __construct(private ContractRepository $repository) {}

    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = isset($queryParams['id']) ? (int)$queryParams['id'] : 0;
        $akcja = isset($queryParams['akcja']) ? (int)$queryParams['akcja'] : 0;
        $sort = isset($queryParams['sort']) ? (int)$queryParams['sort'] : null;

        $withAmountOver10 = ($akcja === 5);
        $contracts = $this->repository->findContracts($id, $sort, $withAmountOver10);

        $html = '<html><body bgcolor="#f7ede2"><br><table width="95%">';
        foreach ($contracts as $contract) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($contract['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($contract['nazwa_przedsiebiorcy']);
            $akcja === 5 ? $html .= ' ' . htmlspecialchars($contract['kwota']) : null;
            $html .= '</td></tr>';
        }
        $html .= '</table></body></html>';

        return new Response(200, ['Content-Type' => 'text/html'], $html);
    }
}

