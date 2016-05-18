<?php

/**
 * Provides configuration options for the SparkPost mail implementation adapter.
 *
 * @author  Hidde Beydals <hello@hidde.co>
 * @license http://www.apache.org/licenses/LICENSE-2.0 APLv2
 */
final class PhabricatorSparkPostConfigOptions
    extends PhabricatorApplicationConfigOptions {

    /**
     * Return the name for the config options.
     *
     * @return string
     */
    public function getName() {
        return pht('Integration with SparkPost');
    }

    /**
     * Return the description for the config options.
     *
     * @return string
     */
    public function getDescription() {
        return pht('Configure SparkPost integration.');
    }

    /**
     * Return the icon for the config options.
     *
     * @return string
     */
    public function getIcon() {
        return 'fa-send-o';
    }

    /**
     * Return the group the config options belong to.
     *
     * @return string
     */
    public function getGroup() {
        return 'core';
    }

    /**
     * Return the config options.
     *
     * @return array
     */
    public function getOptions() {
        return array(
            $this->newOption('sparkpost.api-key', 'string', null)
                ->setHidden(true)
                ->setDescription(pht('SparkPost API key.')),
        );
    }
}