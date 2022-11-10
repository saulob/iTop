.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Examples
--------

Example to generate an temporary information with a spinner (on the real display the spinner is animated)

.. code-block:: twig

    {% UIAlert ForInformation {'sId':'header-requirements', 'IsCollapsible':false, 'IsClosable':false} %}
        {% UIContentBlock Standard {'aContainerClasses':['ibo-update-core-header-requirements'],
                                    'sId':'can-core-update'} %}
            {{ 'iTopUpdate:UI:CanCoreUpdate:Loading'|dict_s }}
            {% UISpinner Standard {} %}
        {% EndUIContentBlock %}
    {% EndUIAlert %}

The information displayed:

.. image:: /manual/Component/Alert/AlertInformationExample.png

The javascript to set a success or a failure in return of an ajax call

.. code-block:: js

    function (data) {
        var oRequirements = $("#header-requirements");
        var oCanCoreUpdate = $("#can-core-update");
        oCanCoreUpdate.html(data.sMessage);
        oRequirements.removeClass("ibo-is-information");
        if (data.bStatus) {
            oRequirements.addClass("ibo-is-success");
        } else {
            oRequirements.addClass("ibo-is-failure");
        }
    }

----

Example to generate a hidden alert to display using javascript in case of error

.. code-block:: twig

    {% UIAlert ForFailure {sId:"dir_error_outer", IsCollapsible:false, IsClosable:false, IsHidden:true} %}
        *The content goes here*
    {% EndUIAlert %}

The javascript to show the alert

.. code-block:: js

    $("#dir_error_outer").removeClass("ibo-is-hidden");

The error displayed:

.. image:: /manual/Component/Alert/AlertFailureExample.png
