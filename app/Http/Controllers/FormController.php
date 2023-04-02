<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;


class FormController extends Controller
{
    private $common;

    public function __construct(Common $common)
    {
        $this->common = $common;
    }

    public function index () {
        return view('form');
    }

    /**
    * Submits the form data and fetches the historical data for the given company symbol and date range
    * @param Request $request The HTTP request object containing form data
    * @return mixed Returns a view containing the historical data
    */
    public function submit(Request $request)
    {
        // Validating the request 
        $request->validate([
            'company_symbol' => 'required|in:'.implode(',', $this::getCompanySymbolsOrName()),
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:'.date('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:'.date('Y-m-d'),
            'email' => 'required|email',
        ],[
            'company_symbol.in' => 'The selected company symbol is invalid.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'start_date.before_or_equal' => 'The start date cannot be after the current date.',
            'end_date.before_or_equal' => 'The end date cannot be after the current date.',
            'start_date.before_or_equal' => 'The start date cannot be after the end date.',
            'end_date.after_or_equal' => 'The end date cannot be before the start date.',
        ]);
        
        $symbol = $request->input('company_symbol');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $email = $request->input('email');

        $companyName = $this::getCompanySymbolsOrName(true, $symbol); //Getting the company name

        $apiData = array('symbol' => $symbol,'region' => 'US');

        try {
            $apiResponse = $this->common->executeAPI(
                '11d5b0f54amsh2325f08132a251fp1828cejsn9691b6cd2125',
                'yh-finance.p.rapidapi.com',
                'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data',
                $apiData
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['api_error' => 'Error fetching data from API.']);
        }
        
        try {
            $body = 'From ' . date('Y-m-d', strtotime($startDate)) . ' to ' . date('Y-m-d', strtotime($endDate));
            $this->common->sendMail($email, $companyName, $body);
        } catch (\Exception $e) {
            return back()->withErrors('Unable to send email. Please try again later.');
        }
    
        // return the data to the view
        return view('result', [
            'historicalData' => $apiResponse['prices']
        ]);
    }


    /**
    * Fetches the list of companies' symbols and names from the NASDAQ listing API and returns either the list of symbols or the name of a specific company

    * @param bool|false $name If false, returns the list of all symbols. If true, returns the name of the company based on symbol

    * @param string $symbol The symbol of the company to search for

    * @return array|string The list of symbols or the name of the company
    */
    public static function getCompanySymbolsOrName ($name = false, $symbol = '') {
        $url = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        $symbols = [];
        $companyName = '';
        foreach ($data as $item) {
            $symbols[] = $item['Symbol'];
            if ($name && !empty($symbol) && $item['Symbol'] == $symbol ) { //If user wants name of the company then set name based on the symbol
                $companyName = $item['Company Name'];
            }
        }
        if ($name) {
            return $companyName;
        } else {
            return $symbols;
        }
    }
}
