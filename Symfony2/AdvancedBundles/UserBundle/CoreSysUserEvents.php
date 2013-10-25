<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle;

/**
 * Contains all the events thrown in the CoreSysUserBundle
 */
/**
 * Class CoreSysUserEvents
 * @package CoreSys\UserBundle
 */
final class CoreSysUserEvents
{

    /**
     *
     */
    const ADMIN_VIEW_USER_INDEX = 'core_sys_user.view.user.index';

    /**
     *
     */
    const ADMIN_VIEW_USER_VIEW = 'core_sys_user.view.user.view';

    /**
     *
     */
    const ADMIN_VIEW_USER_STATS = 'core_sys_user.view.user_stats';

    /**
     *
     */
    const ADMIN_VIEW_ROLE_INDEX = 'core_sys_user.view.role.index';

    /**
     *
     */
    const ADMIN_SAVE_ROLE = 'core_sys_user.save.role';

    /**
     *
     */
    const ADMIN_REMOVE_ROLE_BEFORE = 'core_sys_user.pre.remove.role';

    /**
     *
     */
    const ADMIN_REMOVE_ROLE = 'core_sys_user.remove.role';

    /**
     *
     */
    const ADMIN_CHANGED_ROLE = 'core_sys_user.changed.role';

    /**
     *
     */
    const ADMIN_VIEW_ACCESS_INDEX = 'core_sys_user.view.access.index';

    /**
     *
     */
    const ADMIN_SAVE_ACCESS = 'core_sys_user.save.access';

    /**
     *
     */
    const ADMIN_REMOVE_ACCESS = 'core_sys_user.remove.access';

    /**
     *
     */
    const ADMIN_FORM_USER_CREATE = 'core_sys_user.form.user.create';

    /**
     *
     */
    const ADMIN_FORM_USER_EDIT = 'core_sys_user.form.user.edit';

    /**
     *
     */
    const ADMIN_FORM_ROLE_CREATE = 'core_sys_user.form.role.create';

    /**
     *
     */
    const ADMIN_FORM_ROLE_EDIT = 'core_sys_user.form.role.edit';

    /**
     *
     */
    const ADMIN_FORM_ROLE_SAVE = 'core_sys_user.form.role.save';

    /**
     *
     */
    const ADMIN_FORM_ACCESS_CREATE = 'core_sys_user.form.access.create';

    /**
     *
     */
    const ADMIN_FORM_ACCESS_EDIT = 'core_sys_user.form.access.edit';

    /**
     *
     */
    const ADMIN_FORM_ACCESS_SAVE = 'core_sys_user.form.access.save';

}