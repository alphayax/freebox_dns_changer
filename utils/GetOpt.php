<?php
namespace alphayax\utils;

// Todo test and enrich

$opt = new GetOpt();
$opt->addShortOpt('a', "AAA");
$opt->addShortOpt('b', "BBB");
$opt->addShortOpt('c', "CCC", true);
$opt->addShortOpt('d', "DDD", false, true);
$opt->parse();

/**
 * Class GetOpt
 * @package alphayax\utils
 */
class GetOpt {


    protected $requiredOpt = [];
    private $shortOpt = [];
    private $longOpt = [];

    private $opt_x = [];

    public function __construct(){

    }

    /**
     * @param $optName
     * @param $optDesc
     * @param bool|false $hasValue
     * @param bool|false $isRequired
     */
    public function addLongOpt( $optName, $optDesc, $hasValue = false, $isRequired = false){
        $hasValueFlag = $hasValue ? ':' : '';
        $this->longOpt[ $optName . $hasValueFlag] = $optDesc;
        if( $isRequired){
            $this->requiredOpt[] = $optName;
        }
    }

    /**
     * @param $optLetter
     * @param $optDesc
     * @param bool|false $hasValue
     * @param bool|false $isRequired
     * @throws \Exception
     */
    public function addShortOpt( $optLetter, $optDesc, $hasValue = false, $isRequired = false){
        if( strlen( $optLetter) > 1){
            throw new \Exception( 'Invalid option. A short opt must be a letter [a-zA-Z0-9]');
        }
        $hasValueFlag = $hasValue ? ':' : '';
        $this->shortOpt[ $optLetter . $hasValueFlag] = $optDesc;
        if( $isRequired){
            $this->requiredOpt[] = $optLetter;
        }
    }

    /**
     *
     */
    public function parse(){
        $shortOptz = implode( '', array_keys( $this->shortOpt));
        $longOpt = array_keys( $this->longOpt);
        $this->opt_x = getopt( $shortOptz, $longOpt);

        $givenOpts = array_keys( $this->opt_x);
        $missingOpt = array_diff( $this->requiredOpt, $givenOpts);

        if( ! empty( $missingOpt)){
            throw new \Exception('Required fields missing : '. var_export( $missingOpt, true));
        }
    }

    /**
     * @param $optionName
     * @return mixed
     */
    public function getOptionValue( $optionName){
        return @$this->opt_x[ $optionName];
    }

    /**
     * @param $optionName
     * @return bool
     */
    public function hasOption( $optionName){
        return array_key_exists( $optionName, $this->opt_x);
    }

}