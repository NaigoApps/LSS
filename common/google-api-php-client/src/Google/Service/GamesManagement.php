<?php
/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * Service definition for GamesManagement (v1management).
 *
 * <p>
 * The Management API for Google Play Game Services.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/games/services" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Service_GamesManagement extends Google_Service
{
  /** Share your Google+ profile information and view and manage your game activity. */
  const GAMES =
      "https://www.googleapis.com/auth/games";
  /** Know your basic profile info and list of people in your circles.. */
  const PLUS_LOGIN =
      "https://www.googleapis.com/auth/plus.login";

  public $achievements;
  public $applications;
  public $events;
  public $players;
  public $quests;
  public $rooms;
  public $scores;
  public $turnBasedMatches;
  

  /**
   * Constructs the internal representation of the GamesManagement service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client)
  {
    parent::__construct($client);
    $this->rootUrl = 'https://www.googleapis.com/';
    $this->servicePath = 'games/v1management/';
    $this->version = 'v1management';
    $this->serviceName = 'gamesManagement';

    $this->achievements = new Google_Service_GamesManagement_Achievements_Resource(
        $this,
        $this->serviceName,
        'achievements',
        array(
          'methods' => array(
            'reset' => array(
              'path' => 'achievements/{achievementId}/reset',
              'httpMethod' => 'POST',
              'parameters' => array(
                'achievementId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'resetAll' => array(
              'path' => 'achievements/reset',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'resetAllForAllPlayers' => array(
