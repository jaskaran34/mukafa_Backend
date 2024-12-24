<?php

namespace App\Models;

use App\Traits\HasSchemaAccessors;
use App\Traits\HasCustomShortflakePrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 *
 * Represents a Club in the application.
 */
class Club extends Model
{
    use HasFactory, HasCustomShortflakePrimary, HasSchemaAccessors;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clubs';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should not be exposed by API and other public responses.
     *
     * @var array
     */
    protected $hiddenForPublic = [
        'description',
        'host',
        'slug',
        'location',
        'street1',
        'street2',
        'box_number',
        'postal_code',
        'city',
        'admin1',
        'admin2',
        'geoname_id',
        'region',
        'region_geoname_id',
        'country_code',
        'lat',
        'lng',
        'locale',
        'currency',
        'time_zone',
        'is_active',
        'is_primary',
        'is_undeletable',
        'is_uneditable',
        'meta',
        'deleted_at',
        'deleted_by',
        'created_by',
        'updated_by'
    ];

    public function hideForPublic() 
    {
        $this->makeHidden($this->hiddenForPublic);
    
        return $this;
    }

    /**
     * Allow mass assignment of a model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the partner associated with the club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the cards associated with the club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
