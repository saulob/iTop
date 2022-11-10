.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _CollapsibleSection:

CollapsibleSection
==================

Class CollapsibleSection

----

.. include:: /manual/Component/CollapsibleSection/CollapsibleSectionAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UICollapsibleSection**

:Syntax:

.. code-block:: twig

    {% UICollapsibleSection Type {Parameters} %}
        Content Goes Here
    {% EndUICollapsibleSection %}

:Type:

+----------------------------------------------+-----------------------+
| :ref:`Standard <CollapsibleSectionStandard>` | @param string $sTitle |
+----------------------------------------------+-----------------------+

.. _CollapsibleSectionStandard:

CollapsibleSection Standard
^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UICollapsibleSection Standard {sTitle:'value', sId:'value'} %}
        Content Goes Here
    {% EndUICollapsibleSection %}

:parameters:

+--------+--------+-----------+------+--+
| sTitle | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

CollapsibleSection common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClass                 | string   | CSS class to add to the generated html block               |
+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClasses               | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath           | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddDeferredBlock            | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddHtml                     | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath            | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleCssFilesRelPaths | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleJsFilesRelPaths  | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddSubBlock                 | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| CSSClasses                  | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| DataAttributes              | array    | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+----------+------------------------------------------------------------+
| DeferredBlocks              | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| HasForcedDiv                | bool     |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| IsHidden                    | bool     |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| OpenedByDefault             | bool     |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| SubBlocks                   | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Component/CollapsibleSection/CollapsibleSectionFooter.rst
