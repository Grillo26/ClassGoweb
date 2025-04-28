<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPayoutMethod;
use App\Models\UserWithdrawal;
use Illuminate\Support\Facades\DB;

class PayoutService
{

    public function geWithdrawalBalance($userId)
    {

        return User::select('id')->withSum('pendingWithdrawals as pending_withdrawals', 'amount')
            ->withSum('completedWithdrawals as completed_withdrawals', 'amount')
            ->whereKey($userId)->first();
    }

    public function getWithdrawalTypes($userId)
    {

        return UserWithdrawal::where('user_id', $userId)->select('payout_method', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('payout_method', 'user_id')->get()->keyBy('payout_method');
    }

    public function getWithdrawalDetail($userId, $status = null)
    {
        return UserWithdrawal::where('user_id', $userId)
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })->paginate();
    }

    public function getPayoutStatus($userId)
    {

        $userPayoutMethods = UserPayoutMethod::where('user_id', $userId)
            ->select('status', 'payout_method')
            ->get()
            ->keyBy('payout_method')
            ->toArray();

        return $userPayoutMethods;
    }

    public function addPayoutDetail($userId, $payoutMethod, $payout)
    {
        // Desactivar otros métodos de pago del usuario
        UserPayoutMethod::withTrashed()->where('user_id', $userId)
            ->update(['status' => 'inactive']);

        // Obtener el método de pago actual del usuario (si existe)
        $existingPayout = UserPayoutMethod::withTrashed()
            ->where('user_id', $userId)
            ->where('payout_method', $payoutMethod)
            ->first();

        // Si el método de pago es QR y hay una imagen nueva en $payout
        if ($payoutMethod === 'QR' && isset($payout['img_qr'])) {
            // Eliminar la imagen anterior si existe
            if ($existingPayout && $existingPayout->img_qr) {
                \Storage::disk('public')->delete($existingPayout->img_qr);
            }

            // Guardar la nueva imagen y obtener la ruta
            $payout['img_qr'] = $payout['img_qr']->store('qr_codes', 'public');
        }

        // Crear o actualizar el método de pago
        $payoutDetail = UserPayoutMethod::updateOrCreate(
            ['user_id' => $userId, 'payout_method' => $payoutMethod],
            $payout
        );

        return $payoutDetail;
    }




    public function deletePayout($userId, $payoutMethod)
    {
        $payoutDetail = UserPayoutMethod::where('user_id', $userId)
            ->where('payout_method', $payoutMethod)
            ->first();
        if ($payoutDetail) {
            $payoutDetail->delete();
            return $payoutDetail;
        } else {
            return false;
        }
    }


    public function updatePayoutStatus($userId, $payoutMethod)
    {
        UserPayoutMethod::where('user_id', $userId)->update(['status' => 'inactive']);
        $payoutDetail = UserPayoutMethod::where('user_id', $userId)
            ->where('payout_method', $payoutMethod)
            ->update(['status' => 'active']);
        return $payoutDetail;
    }

    public function activePayoutMethod($userId)
    {
        return UserPayoutMethod::where('user_id', $userId)->where('status', 'active')->first();
    }

    public function updateWithDrawals($userId, $amount)
    {
        $payoutMethod = $this->activePayoutMethod($userId);
        $data = [
            'user_id' => $payoutMethod?->user_id,
            'amount' => $amount,
            'status' => 'pending',
            'payout_method' => $payoutMethod?->payout_method,
            'detail' => $payoutMethod?->payout_details
        ];
        return UserWithdrawal::create($data);
    }

}
