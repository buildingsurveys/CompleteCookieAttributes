<?php
/**
 * Creates multiple invitations from a response
 */

class CompleteCookieAttributes extends PluginBase
{
    protected $storage = 'DbStorage';
    static protected $description = 'completeCookieAttributes';
    static protected $name = 'CompleteCookieAttributes';
    static protected $useActivateSurveyLevel = TRUE;
    protected $settings = array();
    protected static $debugThroughNewDirect = FALSE;

    public function init()
    {
        $this->subscribe('afterSurveyComplete');
    }

    public function afterSurveyComplete()
    {
        $event = $this->getEvent();
        $surveyId = $event->get('surveyId');
        $currentSurvey = Survey::model()->findByPk($surveyId);
        $isSurveyActive = $currentSurvey->getIsActive();
        $usecookie = $currentSurvey->getIsUseCookie();

        $cookieOptions = array (
            'expires' => time() + 31536000, //Cookie will expire in 365 days
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None'
        );
       
        if ($isSurveyActive && $usecookie) {
            if (!$currentSurvey->getHasTokensTable()) {
                setcookie(
                    "LS_" . $surveyId . "_STATUS",
                    "COMPLETE",
                    $cookieOptions
                );
            }
        }
        
    }
}
