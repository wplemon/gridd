( function() {

	// Extends our custom "gridd_plus" section.
	wp.customize.sectionConstructor.gridd_plus = wp.customize.Section.extend({

		// No events for this type of section.
		attachEvents: function() {},

		// Always make the section active.
		isContextuallyActive: function() {
			return true;
		}
	});
}( wp.customize ) );
