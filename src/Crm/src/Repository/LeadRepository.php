<?php

namespace Crm\Repository;

use Crm\Model\Lead;

class LeadRepository
{
    public function getByLeadAccountId(int $leadId, int $accountId): Lead
    {
        $query = Lead::getQuery();
        /** @var Lead $lead */
        $lead = $query->where('account_id', $accountId)
            ->where('lead_id', $leadId)
            ->first();

        return $lead;
    }
}
