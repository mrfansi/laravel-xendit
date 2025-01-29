<?php

namespace Mrfansi\Xendit\Enums;

enum ClientType: string
{
    case API_GATEWAY = 'API_GATEWAY';    // Invoice created via create invoice API
    case DASHBOARD = 'DASHBOARD';        // Invoice created via dashboard
    case INTEGRATION = 'INTEGRATION';    // Invoice created via 3rd party integration
    case ON_DEMAND = 'ON_DEMAND';       // Invoice created via On Demand
    case RECURRING = 'RECURRING';        // Invoice created via Recurring Payment
    case MOBILE = 'MOBILE';             // Invoice created via Mobile App
}
