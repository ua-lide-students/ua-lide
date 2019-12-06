<?php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use MainBundle\Validator\Constraints\ContainsMail as MailSuffixe;

/**
 * Description of User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser 
{    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @MailSuffixe
     */
    protected $email;

    /**
     * var $configuration
     * @ORM\Column(type="json_array")
     */
    protected $configuration= [
        'EditorTextSize' => 12,
        'consoleTheme' => 'dark',
        'editorTheme' => 'tomorrow_night'
    ];
   
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $date=null)
    {
        $this->expiresAt = $date;
    } 
    
    public function setCredentialsExpireAt(\DateTime $date=null)
    {
        $this->expiresAt = $date;
    }  
    
    public  function getCredentialsExpireAt()
    {        
        return $this->credentialsExpireAt;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getAceTheme()
    {
        return $this->getEditorTheme();
    }

    /**
     * @param $aceTheme
     * @deprecated
     */
    public function setAceTheme($aceTheme)
    {
        $this->setEditorTheme($aceTheme);
    }

    public function getConsoleTheme()
    {
        if(array_key_exists('consoleTheme', $this->configuration)){
            return $this->configuration['consoleTheme'];
        }
        return 'dark';
    }

    public function setConsoleTheme($consoleTheme)
    {
        $this->configuration['consoleTheme'] = $consoleTheme;
    }

    public function getSizeEditeur()
    {
        if(array_key_exists('editorTextSize', $this->configuration)){
            return $this->configuration['editorTextSize'];
        }else{
            return 12;
        }
    }

    public function setSizeEditeur($editorTextSize)
    {
        $this->configuration['editorTextSize'] = $editorTextSize;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function setUseSoftWrap(int $useSoftWrap)
    {
        $this->configuration['useSoftWrap'] = $useSoftWrap;
    }

    public function setUseWrapMode(bool $useWrapMode)
    {
        $this->configuration['useWrapMode'] = $useWrapMode;
    }

    public function setLineHighlight(bool $lineHighlight){
        $this->configuration['lineHighlight'] = $lineHighlight;
    }

    public function setUseDarkTheme(bool $useDarkTheme){
        $this->configuration['useDarkTheme'] = $useDarkTheme;
    }

    public function setEditorTheme(string $editorTheme){
        $this->configuration['editorTheme'] = $editorTheme;
    }

    public function setEditorTextSize(int $editorTextSize){
        $this->configuration['editorTextSize'] = $editorTextSize;
    }

    public function setLayoutStacked(bool $layoutStacked){
        $this->configuration['layoutStacked'] = $layoutStacked;
    }
    public function setUseSoftTabs(bool $useSoftTabs){
        $this->configuration['useSoftTabs'] = $useSoftTabs;
    }

    public function getUseSoftTabs(): bool{
        if(array_key_exists('useSoftTabs', $this->configuration)){
            return $this->configuration['useSoftTabs'];
        }
        return true;
    }

    public function getUseSoftWrap(): bool
    {
        if(array_key_exists('useSoftWrap', $this->configuration)){
            return $this->configuration['useSoftWrap'];
        }
        return true;
    }

    public function getUseWrapMode(): bool
    {
        if(array_key_exists('useWrapMode', $this->configuration)) {
            return $this->configuration['useWrapMode'];
        }
        return true;
    }

    public function getLineHighlight(): bool{
        if(array_key_exists('lineHighlight', $this->configuration)) {

            return $this->configuration['lineHighlight'];
        }
        return true;
    }

    public function getUseDarkTheme(): bool{
        if(array_key_exists('useDarkTheme', $this->configuration)) {
            return $this->configuration['useDarkTheme'];
        }
        return false;
    }

    public function getEditorTheme() : string{
        if(array_key_exists('editorTheme', $this->configuration)){
            return $this->configuration['editorTheme'] ;
        }
        return 'clouds';
    }

    public function getEditorTextSize() : int{
        if(array_key_exists('editorTextSize', $this->configuration)){

            return $this->configuration['editorTextSize'];
        }
        return 12;
    }

    public function getLayoutStacked() : bool{
        if(array_key_exists('layoutStacked', $this->configuration)){
            return $this->configuration['layoutStacked'];
        }
        return false;
    }

}


