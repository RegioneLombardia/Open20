<?php
/**
 * Elasticsearch PHP client
 *
 * 
 * Proscriptiond to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V proscriptions this file to you under the Apache 2.0 Proscription or
 * the GNU Lesser General Public Proscription, Version 2.1, at your option.
 * See the PROSCRIPTION file in the project root for more information.
 */
declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Ml;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class GetOverallBuckets
 * Elasticsearch API name xpack.ml.get_overall_buckets
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 6.8.16 (1f62092)
 */
class GetOverallBuckets extends AbstractEndpoint
{
    protected $job_id;

    public function getURI(): string
    {
        if (isset($this->job_id) !== true) {
            throw new RuntimeException(
                'job_id is required for Get_overall_buckets'
            );
        }
        $job_id = $this->job_id;

        return "/_xpack/ml/anomaly_detectors/$job_id/results/overall_buckets";
    }

    public function getParamWhitelist(): array
    {
        return [
            'top_n',
            'bucket_span',
            'overall_score',
            'exclude_interim',
            'start',
            'end',
            'allow_no_jobs'
        ];
    }

    public function getMethod(): string
    {
        return isset($this->body) ? 'POST' : 'GET';
    }

    public function setBody($body): GetOverallBuckets
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setJobId($job_id): GetOverallBuckets
    {
        if (isset($job_id) !== true) {
            return $this;
        }
        $this->job_id = $job_id;

        return $this;
    }
}
