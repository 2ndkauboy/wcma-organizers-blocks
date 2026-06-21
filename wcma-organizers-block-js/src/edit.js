import { __, _x } from '@wordpress/i18n';
import {
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
	ToolbarGroup,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToolsPanel as ToolsPanel,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToolsPanelItem as ToolsPanelItem,
} from '@wordpress/components';
import { grid, list } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';

import './editor.scss';

function Controls( { attributes, setAttributes } ) {
	const {
		showAvatar,
		avatarSize,
		showName,
		nameType,
		showBio,
		showLink,
		linkText,
		displayType,
		gridColumns,
	} = attributes;

	return (
		<>
			<ToolsPanel
				label={ __( 'Avatar', 'wcma-organizers-block' ) }
				resetAll={ () =>
					setAttributes( {
						showAvatar: true,
						avatarSize: 96,
					} )
				}
			>
				<ToolsPanelItem
					hasValue={ () => ! showAvatar }
					label={ __( 'Show avatar', 'wcma-organizers-block' ) }
					onDeselect={ () => setAttributes( { showAvatar: true } ) }
					isShownByDefault
				>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Show avatar', 'wcma-organizers-block' ) }
						checked={ showAvatar }
						onChange={ ( value ) =>
							setAttributes( { showAvatar: value } )
						}
					/>
				</ToolsPanelItem>
				{ showAvatar && (
					<ToolsPanelItem
						hasValue={ () => avatarSize !== 96 }
						label={ __( 'Avatar size', 'wcma-organizers-block' ) }
						onDeselect={ () => setAttributes( { avatarSize: 96 } ) }
						isShownByDefault
					>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __(
								'Avatar size',
								'wcma-organizers-block'
							) }
							value={ avatarSize }
							options={ [
								{
									label: __(
										'Small (48px)',
										'wcma-organizers-block'
									),
									value: 48,
								},
								{
									label: __(
										'Medium (96px)',
										'wcma-organizers-block'
									),
									value: 96,
								},
								{
									label: __(
										'Large (128px)',
										'wcma-organizers-block'
									),
									value: 128,
								},
								{
									label: __(
										'Extra Large (192px)',
										'wcma-organizers-block'
									),
									value: 192,
								},
							] }
							onChange={ ( value ) =>
								setAttributes( {
									avatarSize: parseInt( value, 10 ),
								} )
							}
						/>
					</ToolsPanelItem>
				) }
			</ToolsPanel>

			<ToolsPanel
				label={ __( 'Name', 'wcma-organizers-block' ) }
				resetAll={ () =>
					setAttributes( {
						showName: true,
						nameType: 'display_name',
					} )
				}
			>
				<ToolsPanelItem
					hasValue={ () => ! showName }
					label={ __( 'Show name', 'wcma-organizers-block' ) }
					onDeselect={ () => setAttributes( { showName: true } ) }
					isShownByDefault
				>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Show name', 'wcma-organizers-block' ) }
						checked={ showName }
						onChange={ ( value ) =>
							setAttributes( { showName: value } )
						}
					/>
				</ToolsPanelItem>
				{ showName && (
					<ToolsPanelItem
						hasValue={ () => nameType !== 'display_name' }
						label={ __( 'Name type', 'wcma-organizers-block' ) }
						onDeselect={ () =>
							setAttributes( { nameType: 'display_name' } )
						}
						isShownByDefault
					>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Name type', 'wcma-organizers-block' ) }
							value={ nameType }
							options={ [
								{
									label: __(
										'Display name',
										'wcma-organizers-block'
									),
									value: 'display_name',
								},
								{
									label: __(
										'First name',
										'wcma-organizers-block'
									),
									value: 'first_name',
								},
								{
									label: __(
										'Nickname',
										'wcma-organizers-block'
									),
									value: 'nickname',
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { nameType: value } )
							}
						/>
					</ToolsPanelItem>
				) }
			</ToolsPanel>

			<ToolsPanel
				label={ __( 'Bio', 'wcma-organizers-block' ) }
				resetAll={ () => setAttributes( { showBio: true } ) }
			>
				<ToolsPanelItem
					hasValue={ () => ! showBio }
					label={ __( 'Show bio', 'wcma-organizers-block' ) }
					onDeselect={ () => setAttributes( { showBio: true } ) }
					isShownByDefault
				>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Show bio', 'wcma-organizers-block' ) }
						checked={ showBio }
						onChange={ ( value ) =>
							setAttributes( { showBio: value } )
						}
					/>
				</ToolsPanelItem>
			</ToolsPanel>

			<ToolsPanel
				label={ __( 'Link', 'wcma-organizers-block' ) }
				resetAll={ () =>
					setAttributes( {
						showLink: true,
						linkText: '',
					} )
				}
			>
				<ToolsPanelItem
					hasValue={ () => ! showLink }
					label={ __( 'Show link', 'wcma-organizers-block' ) }
					onDeselect={ () => setAttributes( { showLink: true } ) }
					isShownByDefault
				>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Show link to author archive',
							'wcma-organizers-block'
						) }
						checked={ showLink }
						onChange={ ( value ) =>
							setAttributes( { showLink: value } )
						}
					/>
				</ToolsPanelItem>
				{ showLink && (
					<ToolsPanelItem
						hasValue={ () => linkText !== '' }
						label={ __(
							'Link text prefix',
							'wcma-organizers-block'
						) }
						onDeselect={ () => setAttributes( { linkText: '' } ) }
						isShownByDefault
					>
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __(
								'Link text prefix',
								'wcma-organizers-block'
							) }
							value={ linkText }
							placeholder={ __(
								'Posts by',
								'wcma-organizers-block'
							) }
							onChange={ ( value ) =>
								setAttributes( { linkText: value } )
							}
						/>
					</ToolsPanelItem>
				) }
			</ToolsPanel>

			{ displayType === 'grid' && (
				<ToolsPanel
					label={ __( 'Grid', 'wcma-organizers-block' ) }
					resetAll={ () => setAttributes( { gridColumns: 3 } ) }
				>
					<ToolsPanelItem
						hasValue={ () => gridColumns !== 3 }
						label={ __( 'Columns', 'wcma-organizers-block' ) }
						onDeselect={ () => setAttributes( { gridColumns: 3 } ) }
						isShownByDefault
					>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Columns', 'wcma-organizers-block' ) }
							value={ gridColumns }
							onChange={ ( value ) =>
								setAttributes( { gridColumns: value } )
							}
							min={ 2 }
							max={ 6 }
							required
						/>
					</ToolsPanelItem>
				</ToolsPanel>
			) }
		</>
	);
}

export default function Edit( { attributes, setAttributes } ) {
	const { displayType } = attributes;

	const layoutControls = [
		{
			icon: list,
			title: _x(
				'List view',
				'Authors block display setting',
				'wcma-organizers-block'
			),
			onClick: () => setAttributes( { displayType: 'list' } ),
			isActive: displayType === 'list',
		},
		{
			icon: grid,
			title: _x(
				'Grid view',
				'Authors block display setting',
				'wcma-organizers-block'
			),
			onClick: () => setAttributes( { displayType: 'grid' } ),
			isActive: displayType === 'grid',
		},
	];

	return (
		<>
			<InspectorControls>
				<Controls
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<BlockControls>
				<ToolbarGroup controls={ layoutControls } />
			</BlockControls>
			<div { ...useBlockProps() }>
				<ServerSideRender
					block="wcma/organizers-list-js"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
