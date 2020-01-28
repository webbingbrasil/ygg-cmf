<?php

namespace Ygg\Old\Utils\Testing;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Trait Assertions
 * @package Ygg\Old\Utils\Testing
 */
trait Assertions
{

    /**
     * This function must be called before using Ygg's assertions
     * (in the setUp() for instance)
     */
    protected function initYggAssertions()
    {
        TestResponse::macro('assertYggHasAuthorization', function ($authorization) {
            return $this->assertJson(
                ["authorizations" => [$authorization => true]]
            );
        });

        TestResponse::macro('assertYggHasNotAuthorization', function ($authorization) {
            return $this->assertJson(
                ["authorizations" => [$authorization => false]]
            );
        });

        TestResponse::macro('assertYggFormHasFieldOfType', function ($name, $formFieldClassName) {
            $type = $formFieldClassName::FIELD_TYPE;

            $this->assertJson(
                ["fields" => [$name => ["key" => $name, "type" => $type]]]
            );

            $this->assertYggFormHasFields($name);

            return $this;
        });

        TestResponse::macro('assertYggFormHasFields', function ($names) {

            foreach ((array)$names as $name) {

                $this->assertJson(
                    ["fields" => [$name => ["key" => $name]]]
                );

                $found = false;

                foreach ($this->decodeResponseJson()["layout"]["tabs"] as $tab) {
                    foreach ($tab["columns"] as $column) {
                        foreach ($column["fields"] as $fieldset) {
                            foreach ($fieldset as $field) {
                                if (isset($field["legend"])) {
                                    foreach ($field["fields"] as $fieldsetFields) {
                                        foreach ($fieldsetFields as $field) {
                                            if ($field["key"] == $name) {
                                                $found = true;
                                                break 6;
                                            }
                                        }
                                    }
                                } else if ($field["key"] == $name) {
                                    $found = true;
                                    break 4;
                                }
                            }
                        }
                    }
                }

                if (!$found) {
                    PHPUnit::fail("The field [$name] was not found on the layout part.");
                }
            }

            return $this;
        });

        TestResponse::macro('assertYggFormDataEquals', function ($name, $value) {
            return $this->assertJson(
                ["data" => [$name => $value]]
            );
        });
    }

    /**
     * @param string     $resourceKey
     * @param mixed|null $instanceId
     * @return mixed
     */
    protected function getYggForm(string $resourceKey, $instanceId = null)
    {
        return $this->getJson(
            $instanceId
                ? route("ygg.api.form.edit", [$resourceKey, $instanceId])
                : route("ygg.api.form.create", $resourceKey)
        );
    }

    /**
     * @param string $resourceKey
     * @param        $instanceId
     * @param array  $data
     * @return mixed
     */
    protected function updateYggForm(string $resourceKey, $instanceId, array $data)
    {
        return $this->postJson(
            route("ygg.api.form.update", [$resourceKey, $instanceId]),
            $data
        );
    }

    /**
     * @param string $resourceKey
     * @param array  $data
     * @return mixed
     */
    protected function storeYggForm(string $resourceKey, array $data)
    {
        return $this->postJson(
            route("ygg.api.form.store", $resourceKey),
            $data
        );
    }

    /**
     * @param string $resourceKey
     * @param        $instanceId
     * @param string $commandKey
     * @param array  $data
     * @return mixed
     */
    protected function callInstanceCommand(string $resourceKey, $instanceId, string $commandKey, array $data = [])
    {
        return $this->postJson(
            route(
                "ygg.api.list.command.instance",
                compact('resourceKey', 'instanceId', 'commandKey')
            ),
            ["data" => $data]
        );
    }

    /**
     * @param string $resourceKey
     * @param string $commandKey
     * @param array  $data
     * @return mixed
     */
    protected function callResourceCommand(string $resourceKey, string $commandKey, array $data = [])
    {
        return $this->postJson(
            route("ygg.api.list.command.resource", compact('resourceKey', 'commandKey')),
            ["data" => $data]
        );
    }

    /**
     * @param $user
     */
    protected function loginAsYggUser($user)
    {
        $this->actingAs($user, config("ygg.auth.guard", config("auth.defaults.guard")));
    }
}