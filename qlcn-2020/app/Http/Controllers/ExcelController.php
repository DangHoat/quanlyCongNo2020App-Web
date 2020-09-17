<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\CoverController;
use App\Exports\BillExport;
use App\Exports\ClientExport;
use Excel;
use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExcelController extends Controller
{
	public function exportBill($id){
		function convert_str($str) {
			$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
			$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
			$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
			$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
			$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
			$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
			$str = preg_replace("/(đ)/", 'd', $str);
			$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
			$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
			$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
			$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
			$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
			$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
			$str = preg_replace("/(Đ)/", 'D', $str);
			$str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
			$str = preg_replace("/( )/", '-', $str);
			return $str;
		}
		function current_date(){
			$mday = getdate()['mday'];
			if($mday < 10)  $mday = '0'.$mday;
			$mon = getdate()['mon'];
			if($mon < 10)  $mon = '0'.$mon;
			$year = getdate()['year'];
			return $mday.'-'.$mon.'-'.$year;
		}
		$client = Client::find($id);
		// $name_convert = convert_str($client->name);
		$current_date_convert = current_date();
		$code_convert = convert_str($client->code);
		ob_end_clean();
		ob_start();
    	return Excel::download(new BillExport($id), $code_convert.'_'.$current_date_convert.'.xlsx');
	}

	public function exportClient(){
		function current_date(){
			$mday = getdate()['mday'];
			if($mday < 10)  $mday = '0'.$mday;
			$mon = getdate()['mon'];
			if($mon < 10)  $mon = '0'.$mon;
			$year = getdate()['year'];
			return $mday.'-'.$mon.'-'.$year;
		}
		$name = 'TongHop_'.current_date();
		ob_end_clean();
		ob_start();
    	return Excel::download(new ClientExport(), $name.'.xlsx');
	}
}
//php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"