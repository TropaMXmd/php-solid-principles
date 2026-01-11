The interface segregation principle states:

A client should never be forced to implement an interface that it doesn’t use, or clients shouldn’t be forced to depend on methods they do not use.

This principle emphasizes that large, general-purpose interfaces should be broken down into smaller, more specific ones. This way, client classes only need to know about the methods that are relevant to them.

In this example, if we use only one ShapeInterface for both 2D and 3D shapes and it has both area and volumn method, all the classes (like square, cube) which implements it must implement the volume method, but squares are flat shapes and that they do not have volumes, so this interface would force the Square class to implement a method that it doesn’t need.

This would violate the interface segregation principle. Instead of having one large, monolithic interface, we should create separate, more granular interfaces that define specific capabilities.

To solve this problem, we would rather use Shape2DInterface and Shape3DInterface for 2D and 3D shapes respectively.
