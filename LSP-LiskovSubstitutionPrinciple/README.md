Liskov Substitution Principle (LSP)

Formal definition (simple words)
If a class S is a subtype of class T, then objects of type T should be replaceable with objects of type S without breaking the program.

In practice:

- A child class must honor the contract of its parent

- Method signatures, return types, and behavior expectations must remain compatible

Here, both AreaCalculator and VolumeCalculator can be used interchangeably with ResponseFormatter without any issues, adhering to the Liskov Substitution Principle.

Here sum() method in both calculators works as expected.
✔ Same behavioral contract
✔ Returns float
✔ No inheritance abuse
✔ LSP satisfied
