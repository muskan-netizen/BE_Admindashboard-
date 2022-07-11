<?php
return [
  'MONTHS' => [
    1 => 'Jan',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Apr',
    5 => 'May',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Aug',
    9 => 'Sep',
    10 => 'Oct',
    11 => 'Nov',
    12 => 'Dec'
  ],
  'VendorTypes' => [
    'delivery'     => 'Delivery',           // Delivery of the order will be sent to the ccustomer.
    'dinein'       => 'Dine-In',            // Customer can order and dine in the restaurant.
    'takeaway'     => 'Takeaway',           // Customer can order and take there meal along with them.
    'rental'       => 'Rentals',            // Products which are available for rents will be mentioned in this flow.
    'pick_drop'    => 'Pick & Drop',        // Rides or pickup delivery products will be shown in this flow.
    'on_demand'    => 'On Demand Services', // Services that are available any time you want to use it. 
    'laundry'      => 'Laundry',            // Laundry related products are mentioned in this flow.
  ],
  // VendorTypes database
  // add these fields in table (client_preferences) rental_check,pick_check,on_demand_check,laundry_check
  // add these fields in table (vendors) rental,pick_drop,on_demand,laundry
  // add these fields in table (vendor_slots) rental,on_demand,on_demand,laundry

  'VendorTypesIcon' => [
    'delivery'     => 'deliveryicon',           // Delivery of the order will be sent to the ccustomer.
    'dinein'       => 'dineinicon',            // Customer can order and dine in the restaurant.
    'takeaway'     => 'takeaway',           // Customer can order and take there meal along with them.
    'rental'       => 'rentalicon',            // Products which are available for rents will be mentioned in this flow.
    'pick_drop'    => 'pick_dropicon',        // Rides or pickup delivery products will be shown in this flow.
    'on_demand'    => 'on_demandicon', // Services that are available any time you want to use it. 
    'laundry'      => 'laundryicon',            // Laundry related products are mentioned in this flow.
  ],
];
