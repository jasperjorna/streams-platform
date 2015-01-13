<?php namespace Anomaly\Streams\Platform\Ui\Form\Component\Action;

/**
 * Class ActionNormalizer
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Form\Component\Action
 */
class ActionNormalizer
{

    /**
     * Normalize action input.
     *
     * @param array $actions
     * @param       $prefix
     * @return array
     */
    public function normalize(array $actions, $prefix)
    {
        foreach ($actions as $slug => &$action) {

            /**
             * If the slug is numeric and the action is
             * a string then treat the string as both the
             * action and the slug. This is OK as long as
             * there are not multiple instances of this
             * input using the same action which is not likely.
             */
            if (is_numeric($slug) && is_string($action)) {
                $action = [
                    'slug'   => $action,
                    'action' => $action,
                ];
            }

            /**
             * If the slug is NOT numeric and the action is a
             * string then use the slug as the slug and the
             * action as the action.
             */
            if (!is_numeric($slug) && is_string($action)) {
                $action = [
                    'slug'   => $slug,
                    'action' => $action,
                ];
            }

            /**
             * If the slug is not numeric and the action is an
             * array without a slug then use the slug for
             * the slug for the action.
             */
            if (is_array($action) && !isset($action['slug']) && !is_numeric($slug)) {
                $action['slug'] = $slug;
            }

            /**
             * If the slug is not numeric and the action is an
             * array without a action then use the slug for
             * the action for the action.
             */
            if (is_array($action) && !isset($action['action']) && !is_numeric($slug)) {
                $action['action'] = $slug;
            }

            /**
             * Make sure the attributes array is set.
             */
            $action['attributes'] = array_get($action, 'attributes', []);

            /**
             * If the HREF is present outside of the attributes
             * then pull it and put it in the attributes array.
             */
            if (isset($action['url'])) {
                $action['attributes']['url'] = array_pull($action, 'url');
            }

            /**
             * Set defaults as expected for actions.
             */
            $action['size'] = 'sm';

            $action['attributes']['name']  = $prefix . 'action';
            $action['attributes']['value'] = $action['slug'];
        }

        return $actions;
    }
}
