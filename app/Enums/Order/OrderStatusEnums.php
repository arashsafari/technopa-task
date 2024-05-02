<?php

namespace App\Enums\Order;

use App\Enums\Enum;

enum OrderStatusEnums: string
{
    use Enum;

    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
}
