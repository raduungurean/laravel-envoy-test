<?php

use App\Match;
use App\User;

class MatchesSeeder extends MySeeder
{
    public function run()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $this->importUrl . '/get-matches');

        $jsonMatches = json_decode($res->getBody());

        foreach ($jsonMatches as $jsonMatch) {
            $duration = $jsonMatch->duration;
            $date = $jsonMatch->date;
            $capacity = $jsonMatch->capacity;
            $status = $jsonMatch->status;
            $confirmationsLocked = 0;
            $createdBy = 1;
            $createdAt = $jsonMatch->createdAt;
            $updatedAt = $jsonMatch->updatedAt;
            $teamRedScore = $jsonMatch->teamRedScore;
            $teamBlueScore = $jsonMatch->teamWhiteScore;
            $locationId = 1;
            $groupId = 1;
            $subscriptions = $jsonMatch->subscriptions;

            $match = new Match();
            $match->duration = $duration;
            $match->date = $date;
            $match->capacity = $capacity;
            $match->status = $status;
            $match->confirmations_locked = $confirmationsLocked;
            $match->created_by = $createdBy;
            $match->created_at = $createdAt;
            $match->updated_at = $updatedAt;
            $match->team_red_score = $teamRedScore;
            $match->team_blue_score = $teamBlueScore;
            $match->location_id = $locationId;
            $match->group_id = $groupId;
            if ($match->save()) {
                foreach ($subscriptions as $subscription) {
                    $player = User::where('old_id', $subscription->player_id)->first();
                    if ($player) {
                        $this->command->info($player->username);
                        $team = 'none';
                        if ($subscription->teamColor === 'Red') {
                            $team = 'red';
                        }
                        if ($subscription->teamColor === 'Blue') {
                            $team = 'blue';
                        }
                        $s = new \App\Subscription();
                        $s->user_id = $player->id;
                        $s->match_id = $match->id;
                        $s->team = $team;
                        $s->subscription = $subscription->subscription === 'not-sure' ? 'not-playing' : $subscription->subscription;
                        $s->created_at = $subscription->created_at;
                        $s->updated_at = $subscription->created_at;
                        $s->team_goals = $subscription->goals ? $subscription->goals : 0;
                        $s->save();
                    }
                }
            }
            $this->command->info('added: ' . $jsonMatch->date);
        }

    }
}
