<?php

namespace App\Services;

use Modules\Courses\Models\Course;
use App\Casts\OrderStatusCast;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SlotBooking;
use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;
use App\Models\UserPayoutMethod;

class OrderService {

    public function getOrderDetail($orderId) {
        return Order::find($orderId);
    }

    public function getUserOrderDetail() {
        $order = Order::where('student_id', Auth::user()->id)->first();
        return $order;
    }

    public function getOrders($status, $search, $sortby,$selectedSubject,$selectedSubGroup, $userId = null)
    {
        $orderableTypes = [SlotBooking::class];
        if(Module::has('courses') && Module::isEnabled('courses')){
            $orderableTypes[] = \Modules\Courses\Models\Course::class;
        }
        if(Module::has('subscriptions') && Module::isEnabled('subscriptions')){
            $orderableTypes[] = \Modules\Subscriptions\Models\Subscription::class;
        }
        $orders = OrderItem::withWhereHas('orders' , function($query) use ($status, $userId) {
            $query->select('id', 'status','transaction_id','user_id')->with('userProfile');
            if(!empty($userId) && Auth::user()->hasRole('student')){
                $query->where('user_id' , $userId);
            }
            if (isset(OrderStatusCast::$statuses[$status])) {
                $query->whereStatus(OrderStatusCast::$statuses[$status]);
            }
        })
        ->with('orderable')
        ->whereHasMorph('orderable', $orderableTypes, function ($query, $type) use ($userId) {
            if (!empty($userId) && Auth::user()->hasRole('tutor')) {
                if ($type === SlotBooking::class) {
                    $query->where('tutor_id', $userId)->with(['tutor']);
                } elseif (\Nwidart\Modules\Facades\Module::has('courses') && \Nwidart\Modules\Facades\Module::isEnabled('courses') && $type === \Modules\Courses\Models\Course::class) {
                    $query->where('instructor_id', $userId)->with(['instructor']);
                } elseif (Module::has('subscriptions') && Module::isEnabled('subscriptions') && $type === \Modules\Subscriptions\Models\Subscription::class) {
                    $query->where('role_id', getRoleByName(auth()->user()->role));
                }
            }
        });

        if (!empty($search)) {
            $orders->where(function ($query) use ($search) {
                $query->where('options->subject', $search);
            });
        }


        if (!empty($selectedSubject)) {
            $orders->where(function ($query) use ($selectedSubject) {
                $query->where('options->subject', $selectedSubject);
            });
        }

        if (!empty($selectedSubGroup)) {
            $orders->where(function ($query) use ($selectedSubGroup) {
                $query->where('options->subject_group', $selectedSubGroup);
            });
        }

        $orders = $orders->orderBy('id', $sortby ?? 'asc')
            ->paginate(setting('_general.per_page_opt') ?? 10);

        return $orders;
    }

  public function getBookings($status, $search, $sortby, $selectedSubject, $selectedSubGroup)
{
    $orders = OrderItem::withWhereHas('orders', function ($query) use ($status) {
            $query->select('id', 'status', 'transaction_id', 'user_id')->with('userProfile');
            if (isset(OrderStatusCast::$statuses[$status])) {
                $query->whereStatus(OrderStatusCast::$statuses[$status]);
            }
        })
        ->whereHasMorph('orderable', [SlotBooking::class])
        ->with(['orderable', 'orders.userProfile']); // Aseguramos traer los datos del usuario

    if (!empty($search)) {
        $orders->where(function ($query) use ($search) {
            $query->where('options->subject', $search);
        });
    }

    if (!empty($selectedSubject)) {
        $orders->where(function ($query) use ($selectedSubject) {
            $query->where('options->subject', $selectedSubject);
        });
    }

    if (!empty($selectedSubGroup)) {
        $orders->where(function ($query) use ($selectedSubGroup) {
            $query->where('options->subject_group', $selectedSubGroup);
        });
    }

    // Paginar los resultados
    $orders = $orders->orderBy('id', $sortby ?? 'asc')
        ->paginate(setting('_general.per_page_opt') ?? 10);

    // 🔍 Decodificar `options` y obtener `tutor_id`
    $orders->map(function ($order) {
        // Asegurarnos de que `options` sea un array
        $options = is_string($order->options) ? json_decode($order->options, true) : $order->options;

        // Obtener el `tutor_id` del JSON decodificado
        $tutorId = $options['tutor_id'] ?? null;

      

        // Si no hay tutor, no hay QR
        if (!$tutorId) {
            $order->qr_image = null;
            return $order;
        }

        // Buscar el QR en `user_payout_methods`
        $qrImage = UserPayoutMethod::where('user_id', $tutorId)
            ->where('payout_method', 'QR')
            ->value('img_qr');
            
         

        // Generar la URL del QR si existe
        $order->qr_image = $qrImage ? asset('storage/' . $qrImage) : null;

        return $order;
    });

    return $orders;
}

    

    public function getOrdersList($status, $search, $sortby)
    {
        $orders = Order::with('items')
            ->withSum('items as admin_commission', 'platform_fee')
            ->withCount([
            'items as slot_bookings_count' => function($query) {
                $query->whereHasMorph(
                    'orderable',
                    [SlotBooking::class]
                );
            }
        ]);

        if (isset(OrderStatusCast::$statuses[$status])) {
            $orders->whereStatus(OrderStatusCast::$statuses[$status]);
        }

        if (Module::has('courses') && Module::isEnabled('courses')) {
            $orders->withCount([
                'items as courses_count' => function($query) {
                    $query->whereHasMorph(
                        'orderable',
                        [\Modules\Courses\Models\Course::class]
                    );
                }
            ]);
        };

        if (Module::has('subscriptions') && Module::isEnabled('subscriptions')) {
            $orders->withCount([
                'items as subscriptions_count' => function($query) {
                    $query->whereHasMorph(
                        'orderable',
                        [\Modules\Subscriptions\Models\Subscription::class]
                    );
                }
            ]);
        };

        if (!empty($search)) {
            $orders->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $orders = $orders->orderBy('id', $sortby ?? 'asc')
            ->paginate(setting('_general.per_page_opt') ?? 10);

        return $orders;
    }

    public function getOrdeWrWithItem($id)
    {
        $order = Order::with(['items', 'userProfile', 'countryDetails'])->find($id);
        if($order){
            return $order;
        }
        return false;
    }

    public function createOrder($billingDetail) {
    return  Order::create($billingDetail);
    }

    public function updateOrder ($order , $newDetails) {
        if ($order->update($newDetails)) {
            return $order;
        }
        return false;
    }

    public function storeOrderItems($orderId,$items) {
        $order = Order::find($orderId);
        if ($order) {
            foreach ($items as $item) {
                $order->items()->updateOrCreate(
                    ['orderable_id' => $item['orderable_id'], 'order_id' => $orderId],
                    $item
                );
            }
        }
        return true;
    }


    public function orderItem($tutorId) {
        return Order::whereHas('items', function ($query) use ($tutorId) {
                $query->whereHas('orderable', function ($query) use ($tutorId) {
                    $query->where('tutor_id', $tutorId);
                });
        })->first();
    }

    public function updateOrderItem($orderItem, $newDetails) {
        if ($orderItem->update($newDetails)) {
            return true;
        }
        return false;
    }

    public function getTotalCommission() {
        return OrderItem::sum('platform_fee');
    }

    public function getOrderDetailForPayment($id) {
        return Order::where('unique_payment_id',$id)->first();
    }


}
