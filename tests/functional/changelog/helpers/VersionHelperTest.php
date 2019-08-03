<?php

namespace tests\functional\changelog\services;

use yii\helpers\ArrayHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\vendor\domain\enums\VersionTypeEnum;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\changelog\helpers\CommitHelper;
use yii2rails\extension\changelog\helpers\VersionHelper;
use yii2rails\extension\git\domain\entities\BranchEntity;
use yii2rails\extension\git\domain\entities\GitEntity;
use yii2rails\extension\git\domain\entities\RemoteEntity;
use yii2tool\test\Test\Unit;

class VersionHelperTest extends BaseDomainTest {
	
	const PACKAGE = 'yii2rails/yii2-extension';
    public $package = 'vendor/yii2rails/yii2-extension';
	
	protected function _before() {
		parent::_before();
        DomainHelper::defineDomain('changelog', 'yii2rails\extension\changelog\Domain');
	}
	
	public function testMajor() {
        $commits = [
            'test: remove passing test from blocklist (#29484)',
            'test(ivy): remove passing test from blocklist (#29484)',
            'fix(ivy): TestBed overriding custom ErrorHandler (#29482)',
            'test(bazel): Add router to bazel integration test (#29459)',
            'fix(bazel): workaround problem reading summary files from node_modules',
            'fix(bazel): allow ng_module users to set createExternalSymbolFactoryR…',
            'ci: add `.codefresh/` to the `fw-dev-infra` group (#29478)',
            'fix(ivy): ViewContainerRef.destroy should properly clean the DOM (#29414',
            'fix(compiler): inherit param types when class has a constructor which…',
            'docs: update developer guide for testing and IntelliJ (#29048)',
            'refactor(ivy): use `ClassDeclaration` in more `ReflectionHost`',
            'refactor(ivy): correctly type class declarations in `ngtsc`/`ngcc`',
            'refactor(ivy): implement `DtsModuleScopeResolver` from `MetadataDtsMo…',
            'refactor(ivy): remove unused code from `TypeCheckContext` (#29209)',
            'build: add @npm//jasmine-core dep back to jasmine_node_test in defaul…',
            'build(bazel): also back out of jasmine bootstrap simplification (#29444)',
            'build(bazel): back out of @bazel/jasmine 0.27.7 with shard count (#29444)',
            'build(compiler-cli): enable full TypeScript strictness (#29436)',
            'refactor(service-worker): use `Adapter#parseUrl()` for all URL parsing',
            'test(service-worker): test support for multiple apps on different sub…',
            'test: remove symlink workaround (#29426)',
            'feat(ivy): ngcc - support creating a new copy of the entry-point form…',
            'refactor(ivy): ngcc - extract file writing out into a class (#29092)',
            'feat(ivy): ngcc - support dts compilation via ES5 bundles (#29092)',
            'style(ivy): ngcc - fix typo in comment (#29092)',
            'build: allow build-packages-dist.sh to be run from anywhere (#29092)',
            'breaking change(auth): refactor API',
        ];

        $collection = CommitHelper::parseArray($commits);
        $actual = VersionHelper::getWeight($collection);

        $this->tester->assertEquals($actual, VersionTypeEnum::MAJOR);
	}

    public function testMinor() {
        $commits = [
            'test: remove passing test from blocklist (#29484)',
            'test(ivy): remove passing test from blocklist (#29484)',
            'fix(ivy): TestBed overriding custom ErrorHandler (#29482)',
            'test(bazel): Add router to bazel integration test (#29459)',
            'fix(bazel): workaround problem reading summary files from node_modules',
            'fix(bazel): allow ng_module users to set createExternalSymbolFactoryR…',
            'ci: add `.codefresh/` to the `fw-dev-infra` group (#29478)',
            'fix(ivy): ViewContainerRef.destroy should properly clean the DOM (#29414',
            'fix(compiler): inherit param types when class has a constructor which…',
            'docs: update developer guide for testing and IntelliJ (#29048)',
            'refactor(ivy): use `ClassDeclaration` in more `ReflectionHost`',
            'refactor(ivy): correctly type class declarations in `ngtsc`/`ngcc`',
            'refactor(ivy): implement `DtsModuleScopeResolver` from `MetadataDtsMo…',
            'refactor(ivy): remove unused code from `TypeCheckContext` (#29209)',
            'build: add @npm//jasmine-core dep back to jasmine_node_test in defaul…',
            'build(bazel): also back out of jasmine bootstrap simplification (#29444)',
            'build(bazel): back out of @bazel/jasmine 0.27.7 with shard count (#29444)',
            'build(compiler-cli): enable full TypeScript strictness (#29436)',
            'refactor(service-worker): use `Adapter#parseUrl()` for all URL parsing',
            'test(service-worker): test support for multiple apps on different sub…',
            'test: remove symlink workaround (#29426)',
            'feat(ivy): ngcc - support creating a new copy of the entry-point form…',
            'refactor(ivy): ngcc - extract file writing out into a class (#29092)',
            'feat(ivy): ngcc - support dts compilation via ES5 bundles (#29092)',
            'style(ivy): ngcc - fix typo in comment (#29092)',
            'build: allow build-packages-dist.sh to be run from anywhere (#29092)',
        ];

        $collection = CommitHelper::parseArray($commits);
        $actual = VersionHelper::getWeight($collection);

        $this->tester->assertEquals($actual, VersionTypeEnum::MINOR);
    }

    public function testPatch() {
        $commits = [
            'test: remove passing test from blocklist (#29484)',
            'test(ivy): remove passing test from blocklist (#29484)',
            'fix(ivy): TestBed overriding custom ErrorHandler (#29482)',
            'test(bazel): Add router to bazel integration test (#29459)',
            'fix(bazel): workaround problem reading summary files from node_modules',
            'fix(bazel): allow ng_module users to set createExternalSymbolFactoryR…',
            'ci: add `.codefresh/` to the `fw-dev-infra` group (#29478)',
            'fix(ivy): ViewContainerRef.destroy should properly clean the DOM (#29414',
            'fix(compiler): inherit param types when class has a constructor which…',
            'docs: update developer guide for testing and IntelliJ (#29048)',
            'build: add @npm//jasmine-core dep back to jasmine_node_test in defaul…',
            'build(bazel): also back out of jasmine bootstrap simplification (#29444)',
            'build(bazel): back out of @bazel/jasmine 0.27.7 with shard count (#29444)',
            'build(compiler-cli): enable full TypeScript strictness (#29436)',
            'test(service-worker): test support for multiple apps on different sub…',
            'test: remove symlink workaround (#29426)',
            'style(ivy): ngcc - fix typo in comment (#29092)',
            'build: allow build-packages-dist.sh to be run from anywhere (#29092)',
        ];

        $collection = CommitHelper::parseArray($commits);
        $actual = VersionHelper::getWeight($collection);

        $this->tester->assertEquals($actual, VersionTypeEnum::PATCH);
    }

    public function testIncrementMajor() {
        $oldVersion = '1.3.12';
        $versionEntity = VersionHelper::forgeVersionEntityFromString($oldVersion);
        VersionHelper::incrementVersion($versionEntity, VersionTypeEnum::MAJOR);

        $actual = $versionEntity->toArray();
        $this->assertArray($actual, __METHOD__);
    }

    public function testIncrementMinor() {
        $oldVersion = '1.3.12';
        $versionEntity = VersionHelper::forgeVersionEntityFromString($oldVersion);
        VersionHelper::incrementVersion($versionEntity, VersionTypeEnum::MINOR);

        $actual = $versionEntity->toArray();
        $this->assertArray($actual, __METHOD__);
    }

    public function testIncrementtPatch() {
        $oldVersion = '1.3.12';
        $versionEntity = VersionHelper::forgeVersionEntityFromString($oldVersion);
        VersionHelper::incrementVersion($versionEntity, VersionTypeEnum::PATCH);

        $actual = $versionEntity->toArray();
        $this->assertArray($actual, __METHOD__);
    }
}
