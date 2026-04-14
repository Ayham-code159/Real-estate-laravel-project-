<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingRequestRating extends Model
{
    protected $fillable = [
        'listing_request_id',
        'service_listing_id',
        'buyer_user_id',
        'rating',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function listingRequest(): BelongsTo
    {
        return $this->belongsTo(ListingRequest::class, 'listing_request_id');
    }

    public function serviceListing(): BelongsTo
    {
        return $this->belongsTo(ServiceListing::class);
    }

    public function buyerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }
}
