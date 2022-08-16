<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();
    }
	
	/**
	 * @param array $rows Набор данных из модели
	 * @param array $escape Масссив свойств которые не надо показывать при рендере
	 * @return array Массив готовый к рендеру
	 */
	public function frontGreedsTransform(array $rows, array $escape = []):array
	{
		$resulting_array = [];
		$i=0;
		foreach ($rows as $key=>$val) // Work with single\multi rows response from BD
		{
			foreach ($val as $child_key=>$child_val){
				$hidden = false;
				foreach ($escape as $el) {
					if(is_array($child_val)){
						$esc_names = array_keys($child_val);
						$esc_name = $esc_names[0];
					}
					else{
						$esc_name = $child_key;
					}
					$hidden = $el == $esc_name;
					if($hidden)break;
				}
			/*	if(is_array($child_key)) {
					$name = array_keys($child_val);
					$stuctured_row = ['name' => $name[0], 'value' => $child_val[$name[0]], 'hidden' => $hidden];
					$row_array[] = $stuctured_row;
					$resulting_array[]=$row_array;
				}
				else{*/
					$row_array = ['name' => $child_key, 'value' => $child_val, 'hidden' => $hidden];
					$resulting_array[$i][] = $row_array;
					
				//}
			}
			$i++;
		}
		
		return $resulting_array;
	}
	
	/**
	 * @param array $array Массив данных
	 * @return string Готовый HTML код представления
	 */
	public function arrayToHTML(array $array):string
	{
		$result='<ul>';
		$names = array_keys($array);
		$i=0;
		foreach ($array as $el){
			
			$result.='<li>'.$names[$i]. ' => '.$this->converterArray($el).'</li>';
			$i++;
		}
		$result.='</ul>';
		return $result;
	}
	
	private function converterArray($arr): string
	{
		$return = '<ul>';
		if(is_array($arr)) {
			$name=array_keys($arr);
			$i=0;
			foreach ($arr as $item) {
				$return .= '<li>'.$name[$i].' => ' . (is_array($item) ? $this->converterArray($item) : $item) . '</li>';
				$i++;
			}
		}
		else{
			$return.='<li>'.$arr.'</li>';
		}
		$return .= '</ul>';
		return $return;
	}
}
