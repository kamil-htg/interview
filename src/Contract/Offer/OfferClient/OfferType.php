<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

enum OfferType: string
{
    case ServicedApartment = 'serviced_apartment';
    case HolidayHouse = 'holiday_house';
    case HolidayApartment = 'holiday_apartment';
    case Farm = 'farm';
    case Hotel = 'hotel';
    case HolidayPark = 'holiday_park';
    case Castle = 'castle';
    case Boat = 'boat';
    case MobileHome = 'mobile_home';
    case SharedRoom = 'shared_room';
    case DedicatedRoom = 'dedicated_room';
    case BedAndBreakfast = 'bed_and_breakfast';
    case CampingSuite = 'camping_suite';
    case Hostel = 'hostel';
    case Pension = 'pension';
    case OtherAccommodation = 'other_accommodation';
    case Studio = 'studio';
    case Finca = 'finca';
    case Cottage = 'cottage';
    case Cabin = 'cabin';
    case Condo = 'condo';
    case Gite = 'gite';
    case Villa = 'villa';
    case Aparthotel = 'aparthotel';
    case Chalet = 'chalet';
    case Riad = 'riad';
    case Unusual = 'unusual';
    case Luxury = 'luxury';
    case Bungalow = 'bungalow';
    case Duplex = 'duplex';
    case Lodge = 'lodge';
    case Caravan = 'caravan';
    case Penthouse = 'penthouse';
    case Loft = 'loft';
    case Motel = 'motel';
    case Resort = 'resort';
    case ChambreDhotes = 'chambre_dhotes';
    case Tent = 'tent';
    case Sanatorium = 'sanatorium';
    case Agriturismo = 'agriturismo';
}
