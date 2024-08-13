<?php

namespace Laminas\Mvc\Controller;

use Laminas\Mvc\Exception;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;

/**
 * Basic action controller
 */
abstract class AbstractActionController extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected $eventIdentifier = __CLASS__;

    /**
     * Default action if none provided
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'content' => 'Placeholder page'
        ]);
    }

    /**
     * Action called if matched action does not exist
     *
     * @return ViewModel
     */
    public function notFoundAction()
    {
        $event      = $this->getEvent();
        $routeMatch = $event->getRouteMatch();
        $routeMatch->setParam('action', 'not-found');

        $helper = $this->plugin('createHttpNotFoundModel');
        return $helper($event->getResponse());
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
 
    public function onDispatch(MvcEvent $e) {
    $authService = $this->plugin('auth');
    $routeMatch = $e->getRouteMatch();
    $currentRoute = $routeMatch ? $routeMatch->getMatchedRouteName() : null;
        if(isset($_SESSION['company'])){
             $_SESSION['companyName'] = $_SESSION['company'][0]['companyName'];
        }  
    // dd($authService->ActifCompany());
    // Authentication check
    if (!$authService->hasIdentity()){
        if ($currentRoute !== 'login' && $currentRoute !== 'register') {
            return $this->redirect()->toRoute('login');
        }
    } elseif (!$authService->hasCompany()) {
        $currentAction = $routeMatch ? $routeMatch->getParam('action') : null;
        if ($currentRoute !== 'settingActions' || $currentAction !== 'addcompanyinfo') {
            return $this->redirect()->toRoute('settingActions', ['action' => 'addcompanyinfo']);
        }
    } 
        $company = $authService->Company();
        $hasActiveCompany = false;
        if (!empty($company)){
        foreach ($company as $row) {
            $Companystatus = $row['companyStatus'];

            if ($Companystatus === 'actif') {
                $hasActiveCompany = true;
                break;
            }
        } 
        if ($hasActiveCompany == false) {
             if ($currentRoute !== 'settingActions') {
                 return $this->redirect()->toRoute('settingActions', ['action' => 'selectcompany']);

            }
        }

    }

    if (!$routeMatch) {
        throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
    }

    $action = $routeMatch->getParam('action', 'not-found');
    $method = static::getMethodFromAction($action);

    if (!method_exists($this, $method)) {
        $method = 'notFoundAction';
    }

    $actionResponse = $this->$method();
    $e->setResult($actionResponse);

    return $actionResponse;
}


function numberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        return false;
    }

    if ($number < 0) {
        return $negative . numberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return ucfirst($string);
}

}