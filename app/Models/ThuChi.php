<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuChi extends Model
{
    use HasFactory;
    protected $table = 'thuchi';
    protected $guarded = ['id', 'created_at', 'updated_at']; // other fields is fillablle

    public static function displayThuChiByProject($pid)
    {

        $thuchis = ThuChi::where('pid', $pid)->get();
        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<th class="col-title">Nội dung</th>';
        $html .= '<th class="col-pom">+/-</th>';
        $html .= '<th>Số tiền</th>';
        $html .= '</tr>';
        $sum = 0;

        foreach ($thuchis as $thuchi) {
            $html .= '<tr>';
            $html .= '<td>' . $thuchi->title . '</td>';
            $plus = $thuchi->pom == 1 ? '+' : '-';
            $html .= '<td>' . $plus . '</td>';
            $html .= '<td>' . number_format($thuchi->amount, 0, '.', '.') . '</td>';
            $html .= '</tr>';
            if ($thuchi->pom == 1) {
                $sum += $thuchi->amount;
            } else {
                $sum -= $thuchi->amount;
            }
        }
        $html .= '<tr>';
        $html .= '<th colspan="2" class="col-title">Tổng đơn</th>';
        $html .= '<th>' . number_format($sum, 0, '.', '.') . '</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td><input type="text" placeholder="Nội dung" id="txtTitle" /></td>';
        $html .= '<td>';
        $html .= '<select id="slThuChi">';
        $html .= '<option value="0">Chi</option>';
        $html .= '<option value="1">Thu</option>';
        $html .= '</select>';
        $html .= '</td>';
        $html .= '<td><input type="number" placeholder="Số Tiền" id="txtAmount" /></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="3"><input type="button" value="Thêm" id="btnAddThuChi" pid="' . $pid . '" /></td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }
}
