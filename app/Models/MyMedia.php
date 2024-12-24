<?php

/**
 * In the models, replace:
 * use Spatie\MediaLibrary\MediaCollections\Models\Media;
 * 
 * With:
 * use App\Models\MyMedia as Media;
 * 
 * And in config\media-library.php, replace:
 * 'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
 * 
 * With:
 * 'media_model' => App\Models\MyMedia::class,
 */

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use App\Traits\HandlesMariaDBBigIntegers;

class MyMedia extends BaseMedia {
    use HandlesMariaDBBigIntegers;

    // Add any additional methods or overrides here
}
