/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InnerBlocks, useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { RadioControl, PanelBody } from '@wordpress/components';
import { lockOpen } from '@wordpress/icons';

/**
 * Register the block
 */
registerBlockType('htcul/restricted-content', {
    title: __('Restricted Content', 'hide-this-content-until-login'),
    description: __('Restrict content visibility based on user login status.', 'hide-this-content-until-login'),
    category: 'widgets',
    icon: lockOpen,
    supports: {
        html: false,
        align: true,
    },
    attributes: {
        showTo: {
            type: 'string',
            default: 'logged-in'
        }
    },
    
    /**
     * Edit function for the block
     */
    edit: ({ attributes, setAttributes }) => {
        const { showTo } = attributes;
        const blockProps = useBlockProps({
            className: `htcul-restricted-content htcul-restricted-to-${showTo}`,
        });
        
        return (
            <div {...blockProps}>
                <div className="htcul-restricted-content-header">
                    <span className="htcul-restricted-content-icon">🔒</span>
                    <span className="htcul-restricted-content-title">
                        {showTo === 'logged-in' 
                            ? __('Content visible only to logged-in users', 'hide-this-content-until-login')
                            : __('Content visible only to anonymous visitors', 'hide-this-content-until-login')
                        }
                    </span>
                </div>
                
                <div className="htcul-restricted-content-body">
                    <InnerBlocks />
                </div>
                
                <InspectorControls>
                    <PanelBody
                        title={__('Visibility Settings', 'hide-this-content-until-login')}
                        initialOpen={true}
                    >
                        <RadioControl
                            label={__('Show this content to:', 'hide-this-content-until-login')}
                            selected={showTo}
                            options={[
                                { label: __('Logged-in Users Only', 'hide-this-content-until-login'), value: 'logged-in' },
                                { label: __('Anonymous Visitors Only', 'hide-this-content-until-login'), value: 'anonymous' },
                            ]}
                            onChange={(value) => setAttributes({ showTo: value })}
                        />
                    </PanelBody>
                </InspectorControls>
            </div>
        );
    },
    
    /**
     * Save function for the block
     */
    save: () => {
        return (
            <div {...useBlockProps.save()}>
                <InnerBlocks.Content />
            </div>
        );
    },
});

/**
 * Add editor styles
 */
import './editor.scss';