# Elastic stack (ELK) + Symfony + PostgresSQL on Docker

Run the latest version of the [Elastic stack][elk-stack] with Docker and Docker Compose.

It gives you the ability to analyze any data set by using the searching/aggregation capabilities of Elasticsearch and
the visualization power of Kibana.

Based on the [official Docker images][elastic-docker] from Elastic:

* [Elasticsearch](https://github.com/elastic/elasticsearch/tree/main/distribution/docker)
* [Logstash](https://github.com/elastic/logstash/tree/main/docker)
* [Kibana](https://github.com/elastic/kibana/tree/main/src/dev/build/tasks/os_packages/docker_generator)

Other available stack variants:

* [`tls`](https://github.com/deviantony/docker-elk/tree/tls): TLS encryption enabled in Elasticsearch, Kibana (opt in),
  and Fleet

> [!IMPORTANT]
> [Platinum][subscriptions] features are enabled by default for a [trial][license-mngmt] duration of **30 days**. After
> this evaluation period, you will retain access to all the free features included in the Open Basic license seamlessly,
> without manual intervention required, and without losing any data. Refer to the [How to disable paid
> features](#how-to-disable-paid-features) section to opt out of this behaviour.

---

## Setup ELK

```sh
docker compose up setup
```

```sh
docker compose -f compose.yml -f compose.override.yml -f docker/extensions/filebeat/filebeat-compose.yml up -d
```
