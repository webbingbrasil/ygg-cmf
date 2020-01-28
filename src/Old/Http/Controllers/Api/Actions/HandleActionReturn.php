<?php

namespace Ygg\Old\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Stream;
use Ygg\Old\Dashboard\Dashboard;
use Ygg\Old\Resource\Resource;
use Ygg\Old\Resource\ResourceQueryParams;

/**
 * Trait HandleCommandReturn
 * @package Ygg\Old\Http\Controllers\Api\Actions
 */
trait HandleActionReturn
{

    /**
     * @param Dashboard|Resource $actionContainer
     * @param array $returnedValue
     * @return JsonResponse|Stream
     */
    protected function returnActionResult($actionContainer, array $returnedValue)
    {
        if ($returnedValue['action'] === 'download') {
            // Download case is specific: we return a File Stream
            return response()->download(
                $returnedValue['file'],
                $returnedValue['name']
            )->deleteFileAfterSend($returnedValue['shouldDelete']);
        }

        if ($actionContainer instanceof Resource && $returnedValue['action'] === 'refresh') {
            // We have to load and build items from ids
            $returnedValue['items'] = $actionContainer->getData(
                $actionContainer->data(
                    ResourceQueryParams::createFromArrayOfIds(
                        $returnedValue['items']
                    )
                        ->setDefaultSort($actionContainer->getDefaultSort(), $actionContainer->getDefaultSortDir())
                )
            )['items'];
        }

        return response()->json($returnedValue);
    }
}
