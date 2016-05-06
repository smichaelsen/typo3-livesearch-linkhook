<?php
namespace Smichaelsen\LivesearchLinkhook;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class LiveSearch extends \TYPO3\CMS\Backend\Search\LiveSearch\LiveSearch
{

    const SIGNAL_editLink = 1462530607;

    /**
     * Overwrites the parent method and offers a signal to modify the editlink
     *
     * @param string $tableName Record table name
     * @param array $row Current record row from database.
     * @return string Link to open an edit window for record.
     * @see \TYPO3\CMS\Backend\Utility\BackendUtility::readPageAccess()
     */
    protected function getEditLink($tableName, $row)
    {
        $editLink = parent::getEditLink($tableName, $row);
        if (!empty($editLink)) {
            $slotsResult = GeneralUtility::makeInstance(ObjectManager::class)->get(Dispatcher::class)->dispatch(
                __CLASS__,
                self::SIGNAL_editLink,
                [
                    'editLink' => $editLink,
                    'tableName' => $tableName,
                    'row' => $row,
                ]
            );
            $editLink = $slotsResult['editLink'];
        }
        return $editLink;
    }

}
