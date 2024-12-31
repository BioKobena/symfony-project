<?php

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Company;
use App\Entity\FicheDePoste;

class MatchingService
{
    public function calculateMatchScore(Developer $developer, FicheDePoste $job): ?int
    {
        $score = 0;
        $maxScore = 0;

        // Matching des technologies
        // $maxScore += count($job->getTechnologies()) * 3;
        // foreach ($job->getTechnologies() as $tech) {
        //     if (in_array($tech, $developer->getTechnologies())) {
        //         $score += 3;
        //     }
        // }

        // Matching du salaire
        $maxScore += 5;
        if (
            $job->getSalairePropose() <= $developer->getSalaireMin()
            // &&
            // $job->getSalaireMax() >= $developer->getSalaireMin()
        ) {
            $score += 5;
        }

        // Matching de la localisation
        $maxScore += 2;
        // if ($developer->getLocalisation() === $job->getLocalisation() || $job->getLocalisation() === 'Télétravail') {
        if ($developer->getLocalisation() === $job->getLocalisation() || $job->getLocalisation() === 'Télétravail') {
            $score += 2;
        }

        // Matching de l'expérience
        $maxScore += 4;
        if ($developer->getExperience() === $job->getNiveauExperience()) {
            $score += 4;
        }

        // Calcul du pourcentage
        return (int) round(($score / $maxScore) * 100);
    }

    public function calculateDeveloperMatchScore(Company $entreprise, Developer $developer): float
    {
        $score = 0;
        $maxScore = 100;

        // 2. Localisation
        if ($developer->getLocalisation() === $entreprise->getLocalisation()) {
            $score += 10;
        }

        // 3. Salaire
        $developerSalary = $developer->getSalaireMin();
        foreach ($entreprise->getFichesDePostes() as $job) {
            $salaryRange = $job->getSalairePropose();
            if ($developerSalary >= $salaryRange && $developerSalary <= $salaryRange) {
                $score += 20;
                break;
            }
        }

        // 4. Expérience
        foreach ($entreprise->getFichesDePostes() as $job) {
            $experienceDiff = abs($developer->getExperience() - $job->getNiveauExperience());
            $score += max(0, 20 - $experienceDiff * 5); // Exemple : 20 points max, réduit par différence d'expérience
        }

        return min($score, $maxScore); // Limiter le score à 100
    }
}
