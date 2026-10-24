<?php
namespace App\Utils;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class Thumbnail
{
    // same sizes as the old liip_imagine filter sets (thumbnail, outbound, allow_upscale, quality 75)
    private const FILTERS = [
        'carousel_thumb_out' => [1140, 700],
        'big_thumb_out' => [567, 369],
        'medium_thumb_out' => [400, 300],
    ];

    /**
     * Resize image in place.
     *
     * @param string $image Path to image which should be thumbnailed.
     * @param string $filter Filter to use for making thumbnail
     */
    public function create(string $image, string $filter): void
    {
        [$width, $height] = self::FILTERS[$filter];

        (new Imagine())->open($image)
            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND | ImageInterface::THUMBNAIL_FLAG_UPSCALE)
            ->save($image, ['quality' => 75]);
    }
}
