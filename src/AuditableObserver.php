<?php
/**
 * This file is part of the Laravel Auditing package.
 *
 * @author     Antério Vieira <anteriovieira@gmail.com>
 * @author     Quetzy Garcia  <quetzyg@altek.org>
 * @author     Raphael França <raphaelfrancabsb@gmail.com>
 * @copyright  2015-2018
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

namespace JP\Audit;

use JP\Audit\Contracts\Auditable;
use JP\Audit\Facades\Auditor;

class AuditableObserver
{
    /**
     * Is the model being restored?
     *
     * @var bool
     */
    public static $restoring = false;

    /**
     * Handle the retrieved event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */
    public function retrieved(Auditable $model)
    {
        Auditor::execute($model->setAuditEvent('retrieved'));
    }

    /**
     * Handle the created event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */
    public function created(Auditable $model)
    {
        Auditor::execute($model->setAuditEvent('created'));
    }

    /**
     * Handle the updated event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */

    public function updated(Auditable $model)
    {
        if (!static::$restoring) {
            Auditor::execute($model->setAuditEvent('updated'));
        }
    }

    /**
     * Handle the deleted event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */
    public function deleted(Auditable $model)
    {
        Auditor::execute($model->setAuditEvent('deleted'));
    }

    /**
     * Handle the restoring event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */
    public function restoring(Auditable $model)
    {
        // When restoring a model, an updated event is also fired.
        // By keeping track of the main event that took place,
        // we avoid creating a second audit with wrong values
        static::$restoring = true;
    }

    /**
     * Handle the restored event.
     *
     * @param \JP\Audit\Contracts\Auditable $model
     *
     * @return void
     */
    public function restored(Auditable $model)
    {
        Auditor::execute($model->setAuditEvent('restored'));

        // Once the model is restored, we need to put everything back
        // as before, in case a legitimate update event is fired
        static::$restoring = false;
    }
}
